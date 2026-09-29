<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\RecordsAuditLog;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use RecordsAuditLog;

    /**
     * Daftar seluruh pengguna.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
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
     * Ubah peran (role) pengguna.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => 'required|in:Admin,Manager,Gudang,Customer',
        ]);

        if ($user->id === auth()->id() && $validated['role'] !== 'Admin') {
            return back()->with('error', 'Anda tidak dapat menurunkan peran akun Anda sendiri.');
        }

        $old = ['role' => $user->role];
        $user->update($validated);

        $this->recordAudit('Ubah Peran Pengguna: '.$user->email, $user->id, $old, $validated);

        return back()->with('success', 'Peran pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna (tidak dapat menghapus akun sendiri).
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $this->recordAudit('Hapus Pengguna: '.$user->email, $user->id, ['name' => $user->name, 'email' => $user->email, 'role' => $user->role], null);

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
