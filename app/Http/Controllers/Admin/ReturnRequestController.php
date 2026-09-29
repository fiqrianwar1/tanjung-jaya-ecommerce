<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Support\RecordsAuditLog;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    use RecordsAuditLog;

    /**
     * Daftar pengajuan retur.
     */
    public function index(Request $request)
    {
        $query = ReturnRequest::with(['order.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()->paginate(10)->withQueryString();

        return view('admin.returns.index', compact('returns'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Verifikasi retur: setujui atau tolak.
     * Jika disetujui, stok produk dikembalikan & pesanan ditandai selesai.
     */
    public function update(Request $request, string $id)
    {
        $return = ReturnRequest::with('order.orderItems.product')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:Menunggu Verifikasi,Disetujui,Ditolak,Selesai',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $old = ['status' => $return->status];

        $return->update([
            'status' => $validated['status'],
            'notes' => $validated['admin_notes'] ? trim(($return->notes ?? '')."\n\nCatatan Admin: ".$validated['admin_notes']) : $return->notes,
        ]);

        if ($validated['status'] === 'Disetujui' && $return->order) {
            // Kembalikan stok produk yang diretur
            foreach ($return->order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->qty);
                    $item->product->logStock('in', $item->qty, auth()->id());
                }
            }

            $return->order->update(['status' => 'completed']);
        } elseif ($validated['status'] === 'Ditolak' && $return->order) {
            // Kembalikan status pesanan ke selesai (retur dibatalkan)
            $return->order->update(['status' => 'completed']);
        }

        $this->recordAudit('Verifikasi Retur #'.$return->id, $return->id, $old, $validated);

        return back()->with('success', 'Status retur berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $return = ReturnRequest::findOrFail($id);

        // Pulihkan status pesanan sebelum retur dihapus
        if ($return->order && $return->order->status === 'returned') {
            $return->order->update(['status' => 'completed']);
        }

        $this->recordAudit('Hapus Retur #'.$return->id, $return->id, ['reason' => $return->reason], null);

        $return->delete();

        return back()->with('success', 'Pengajuan retur berhasil dihapus.');
    }
}
