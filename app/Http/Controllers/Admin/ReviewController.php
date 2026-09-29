<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Support\RecordsAuditLog;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use RecordsAuditLog;

    /**
     * Moderasi semua ulasan pelanggan.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(12)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
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
     * Publikasikan / sembunyikan ulasan, serta balas ulasan sebagai admin.
     */
    public function update(Request $request, string $id)
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'status' => 'nullable|in:published,hidden',
            'admin_reply' => 'nullable|string|max:1000',
        ]);

        $old = $review->only(['status', 'admin_reply']);

        $review->update(array_filter($validated, fn ($value) => $value !== null));

        $this->recordAudit('Moderasi Ulasan #'.$review->id, $review->id, $old, $validated);

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    /**
     * Hapus ulasan.
     */
    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);

        $this->recordAudit('Hapus Ulasan #'.$review->id, $review->id, $review->only(['rating', 'comment']), null);

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
