<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = User::where('role', 'petugas')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.petugas.index', compact('petugas'));
    }

    public function create()
    {
        $petugas = new User();
        return view('admin.petugas.form', compact('petugas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nip' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        $validated['role'] = 'petugas';
        $validated['is_active'] = true;
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas Lapangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $petugas = User::findOrFail($id);
        
        if ($petugas->role !== 'petugas') {
            abort(403, 'Akses ditolak.');
        }

        return view('admin.petugas.form', compact('petugas'));
    }

    public function update(Request $request, $id)
    {
        $petugas = User::findOrFail($id);
        
        if ($petugas->role !== 'petugas') {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($petugas->id)],
            'nip' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $petugas->update($validated);

        return redirect()->route('admin.petugas.index')->with('success', 'Data Petugas Lapangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $petugas = User::findOrFail($id);
        
        if ($petugas->role !== 'petugas') {
            abort(403, 'Akses ditolak.');
        }
        
        if ($petugas->assignedReports()->count() > 0) {
            return redirect()->route('admin.petugas.index')->with('error', 'Petugas tidak dapat dihapus karena pernah/sedang menangani laporan.');
        }

        $petugas->delete();

        return redirect()->route('admin.petugas.index')->with('success', 'Petugas Lapangan berhasil dihapus.');
    }
}
