@extends('layouts.admin')

@section('title', 'Manajemen Petugas Lapangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Petugas Lapangan</h4>
        <p class="text-muted mb-0 small">Kelola data petugas yang bertugas menangani laporan di lapangan.</p>
    </div>
    <a href="{{ route('admin.petugas.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Petugas
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 border-0 py-3 rounded-top-start">Nama / NIP</th>
                        <th class="border-0 py-3">Kontak</th>
                        <th class="border-0 py-3">Tugas Aktif</th>
                        <th class="border-0 py-3 text-end pe-4 rounded-top-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($petugas as $p)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 45px; height: 45px; font-weight: bold;">
                                    {{ substr($p->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $p->name }}</h6>
                                    <span class="text-muted small">NIP: {{ $p->nip ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="mb-1"><i class="bi bi-envelope text-muted me-1"></i> {{ $p->email }}</div>
                            <div class="small"><i class="bi bi-whatsapp text-muted me-1"></i> {{ $p->phone ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                {{ $p->activeAssignedReportsCount() }} Laporan
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('admin.petugas.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.petugas.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus petugas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 d-block mb-3 opacity-50"></i>
                            Belum ada data Petugas Lapangan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($petugas->hasPages())
    <div class="card-footer bg-white border-top border-light rounded-bottom-4 py-3">
        {{ $petugas->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
