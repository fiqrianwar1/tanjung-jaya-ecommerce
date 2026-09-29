<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ReportSubscription;
use Illuminate\Http\Request;

class ReportSubscriptionController extends Controller
{
    /**
     * Daftar langganan laporan milik manager yang sedang login.
     */
    public function index()
    {
        $subscriptions = ReportSubscription::where('manager_id', auth()->id())->latest()->get();

        return view('manager.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        return redirect()->route('manager.subscriptions.index');
    }

    /**
     * Berlangganan laporan otomatis.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:Laporan Penjualan,Laporan Stok,Laporan Retur',
            'frequency' => 'required|in:Harian,Mingguan,Bulanan',
        ]);

        ReportSubscription::create([
            'manager_id' => auth()->id(),
            'report_type' => $validated['report_type'],
            'frequency' => $validated['frequency'],
        ]);

        return back()->with('success', 'Langganan laporan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        return redirect()->route('manager.subscriptions.index');
    }

    public function update(Request $request, string $id)
    {
        $subscription = ReportSubscription::where('manager_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'report_type' => 'required|in:Laporan Penjualan,Laporan Stok,Laporan Retur',
            'frequency' => 'required|in:Harian,Mingguan,Bulanan',
        ]);

        $subscription->update($validated);

        return back()->with('success', 'Langganan laporan berhasil diperbarui.');
    }

    /**
     * Hentikan langganan laporan.
     */
    public function destroy(string $id)
    {
        $subscription = ReportSubscription::where('manager_id', auth()->id())->findOrFail($id);
        $subscription->delete();

        return back()->with('success', 'Langganan laporan berhasil dihentikan.');
    }
}
