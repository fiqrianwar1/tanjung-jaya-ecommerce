<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\GroqApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatbotController extends Controller
{
    protected GroqApiService $groqService;

    /**
     * Maksimum jumlah pesan percakapan yang dikirim ulang ke API.
     * 10 pesan = 5 percakapan user/assistant terakhir.
     */
    private const MAX_HISTORY = 10;

    public function __construct(GroqApiService $groqService)
    {
        $this->groqService = $groqService;
    }

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array|max:40',
            'history.*.role' => 'nullable|string|in:user,assistant,system',
            'history.*.content' => 'nullable|string|max:4000',
        ]);

        $userMessage = trim($validated['message']);

        if ($userMessage === '') {
            return response()->json([
                'response' => 'Pesan tidak boleh kosong ya bro. Silakan tulis pertanyaan Anda.',
            ]);
        }

        try {
            $messages = $this->buildMessages($validated['history'] ?? []);
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            $response = $this->groqService->sendMessage($messages, $this->systemPrompt());

            return response()->json(['response' => $response]);
        } catch (Throwable $e) {
            Log::error('Chatbot failure: '.$e->getMessage());

            return response()->json([
                'response' => 'Maaf bro, lagi ada gangguan sistem. Coba lagi sebentar ya!',
            ], 500);
        }
    }

    /**
     * Susun riwayat percakapan agar asisten punya konteks lanjutan & tidak gampang lupa.
     *
     * @param  array<int, array<string, mixed>>  $history
     * @return array<int, array{role: string, content: string}>
     */
    private function buildMessages(array $history): array
    {
        $messages = [];

        foreach ($history as $msg) {
            $role = $msg['role'] ?? null;
            // Riwayat dari client hanya boleh berupa user/assistant (system prompt diisi server)
            if (! in_array($role, ['user', 'assistant'], true)) {
                continue;
            }

            $content = trim((string) ($msg['content'] ?? ''));
            if ($content === '') {
                continue;
            }

            $messages[] = ['role' => $role, 'content' => $content];
        }

        // Ambil pesan terbaru saja agar payload tetap ringan
        return array_slice($messages, -self::MAX_HISTORY);
    }

    /**
     * System prompt + konteks katalog nyata supaya rekomendasi & harga tidak mengarang.
     */
    private function systemPrompt(): string
    {
        $prompt = "Kamu adalah asisten virtual resmi untuk 'Tanjung Jaya Corporation' (PT. Warna Tanjung Jaya & PT. Sumber Tanjung Jaya), "
            .'toko bangunan dan peralatan rumah tangga di Jl. Haryono MT No 12-14, Kertak Baru Ilir, Banjarmasin. '
            ."Telepon toko: 0511-3362552 / 3360492.\n\n"
            .'Tugas utamamu HANYA membantu seputar belanja di Tanjung Jaya: merekomendasikan produk, memberi tahu harga produk, '
            ."menjelaskan ketersediaan stok, kegunaan barang, dan cara berbelanja/checkout di website ini.\n"
            .'JANGAN menjawab pertanyaan di luar konteks toko Tanjung Jaya, barang bangunan, atau peralatan rumah tangga '
            .'(termasuk pertanyaan umum, curhat, politik, atau topik lain). Tolak dengan sopan dan katakan bahwa kamu hanya melayani '
            ."pertanyaan seputar produk Tanjung Jaya.\n\n"
            ."ATURAN PENTING:\n"
            ."1. Gunakan HANYA data produk pada daftar di bawah ini. JANGAN mengarang nama produk, harga, atau stok.\n"
            .'2. Jika produk yang dicari tidak ada di daftar, katakan bahwa barang tersebut belum tersedia di katalog online dan '
            ."sarankan menghubungi toko lewat telepon atau media sosial resmi.\n"
            ."3. Selalu tulis harga dalam format Rupiah, contoh: Rp 125.000.\n"
            ."4. Cara berbelanja: buka halaman katalog, pilih produk, klik 'Masukkan Keranjang', lalu buka Keranjang dan klik "
            ."'Checkout Sekarang'. Pesanan akan diproses Gudang lalu dikirim.\n"
            ."5. Jawab singkat, padat, jelas, dan ramah. Gunakan bahasa Indonesia santai namun sopan.\n\n";

        $katalog = $this->catalogContext();

        return $katalog !== ''
            ? $prompt."=== DAFTAR PRODUK TERSEDIA DI TANJUNG JAYA ===\n".$katalog
            : $prompt.'(Saat ini belum ada produk aktif yang bisa direkomendasikan, arahkan pembeli untuk menghubungi toko.)';
    }

    /**
     * Ringkasan katalog (maksimal 40 produk) untuk dijadikan konteks model.
     */
    private function catalogContext(): string
    {
        try {
            $products = Product::with('category')
                ->where('status', 'active')
                ->orderBy('name')
                ->take(40)
                ->get();

            if ($products->isEmpty()) {
                return '';
            }

            return $products->map(function (Product $product) {
                $kategori = $product->category->name ?? 'Umum';
                $status = $product->stock > 0 ? "stok {$product->stock}" : 'stok habis';

                return sprintf(
                    '- %s (kategori: %s) | harga: Rp %s | %s',
                    $product->name,
                    $kategori,
                    number_format((float) $product->price, 0, ',', '.'),
                    $status
                );
            })->implode("\n");
        } catch (Throwable $e) {
            Log::warning('Gagal memuat konteks katalog chatbot: '.$e->getMessage());

            return '';
        }
    }
}
