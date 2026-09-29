<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returns = ReturnRequest::with(['order'])
            ->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return view('customer.returns.index', compact('returns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'item_name' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($validated['order_id']);

        // Hanya pesanan yang sudah dikirim / selesai & belum pernah diretur yang boleh mengajukan
        if (! in_array($order->status, ['shipped', 'completed'])) {
            return back()->with('error', 'Status pesanan tidak mengizinkan retur.');
        }

        if ($order->returnRequest()->exists()) {
            return back()->with('warning', 'Pesanan ini sudah pernah diajukan retur.');
        }

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('returns', 'public');
        }

        ReturnRequest::create([
            'order_id' => $order->id,
            'reason' => $validated['reason'].' — '.$validated['item_name'],
            'notes' => $validated['description'],
            'proof_image' => $proofPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        $order->update(['status' => 'returned']);

        return back()->with('success', 'Pengajuan retur berhasil dikirim. Menunggu verifikasi admin.');
    }
}
