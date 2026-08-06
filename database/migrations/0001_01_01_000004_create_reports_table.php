<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Laporan Pengaduan (Reports)
 * 
 * Tabel utama menyimpan data pengaduan dari warga.
 * Termasuk data pelapor, lokasi GPS (lat/lng), status,
 * dan penunjukan petugas (disposisi).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Kode tiket unik: DLH-YYYYMMDD-XXX
            $table->string('ticket_code', 20)->unique();

            // Data pelapor (tanpa perlu login)
            $table->string('reporter_name', 100);
            $table->string('reporter_phone', 20)->comment('Nomor WhatsApp pelapor');
            $table->string('reporter_email', 100)->nullable();

            // Kategori & deskripsi
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->text('description');

            // Lokasi GPS & alamat
            $table->decimal('latitude', 10, 8)->comment('Koordinat lintang lokasi');
            $table->decimal('longitude', 11, 8)->comment('Koordinat bujur lokasi');
            $table->text('address')->comment('Alamat lengkap/deskripsi lokasi');
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();

            // Status & prioritas
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->enum('priority', ['rendah', 'sedang', 'tinggi', 'darurat'])->default('sedang');

            // Disposisi ke petugas
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('assigned_at')->nullable();

            // Catatan admin & resolusi
            $table->text('admin_notes')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            // Index untuk pencarian & filter
            $table->index('status');
            $table->index('kecamatan');
            $table->index('reporter_phone');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
