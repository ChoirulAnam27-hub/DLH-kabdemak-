@extends('layouts.admin')

@section('title', $petugas->exists ? 'Edit Petugas' : 'Tambah Petugas')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.petugas.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Petugas
    </a>
    <h4 class="mb-0 fw-bold">{{ $petugas->exists ? 'Edit Petugas Lapangan' : 'Tambah Petugas Lapangan Baru' }}</h4>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form action="{{ $petugas->exists ? route('admin.petugas.update', $petugas->id) : route('admin.petugas.store') }}" method="POST">
            @csrf
            @if($petugas->exists)
                @method('PUT')
            @endif

            <div class="row gy-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $petugas->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">NIP</label>
                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $petugas->nip) }}" placeholder="Opsional">
                    @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $petugas->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">No. WhatsApp</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $petugas->phone) }}" placeholder="Contoh: 081234567890">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Password {!! !$petugas->exists ? '<span class="text-danger">*</span>' : '' !!}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ !$petugas->exists ? 'required' : '' }}>
                    @if($petugas->exists)
                        <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                    @endif
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr class="my-4">
            
            <div class="text-end">
                <a href="{{ route('admin.petugas.index') }}" class="btn btn-light px-4 me-2">Batal</a>
                <button type="submit" class="btn btn-success px-4 fw-bold">
                    <i class="bi bi-save me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
