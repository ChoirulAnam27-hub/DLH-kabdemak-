<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel laporan pengaduan sampah & pencemaran.
 * Ini adalah tabel utama yang menyimpan seluruh data pengaduan warga,
 * termasuk koordinat GPS, foto bukti, status penanganan, dan disposisi petugas.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Kode tiket unik: DLH-YYYYMMDD-XXX
            $table->string('ticket_code', 20)->unique();

            // Data pelapor (tanpa perlu registrasi akun)
            $table->string('reporter_name', 100);
            $table->string('reporter_phone', 20); // Nomor WhatsApp
            $table->string('reporter_email', 100)->nullable();

            // Kategori pengaduan
            $table->enum('category', [
                'sampah',
                'pencemaran_air',
                'pencemaran_udara',
                'lainnya'
            ])->default('sampah');

            // Deskripsi detail masalah
            $table->text('description');

            // Lokasi GPS (koordinat spasial)
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->text('address'); // Alamat lengkap (dari reverse geocode atau manual)
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();

            // Foto bukti pengaduan
            $table->string('photo_path', 255);

            // Status & prioritas penanganan
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->enum('priority', ['rendah', 'sedang', 'tinggi', 'darurat'])->default('sedang');

            // Disposisi ke petugas lapangan
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            // Bukti penyelesaian
            $table->string('resolved_photo', 255)->nullable();
            $table->text('resolved_note')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            // Index untuk pencarian & filter
            $table->index('status');
            $table->index('category');
            $table->index('kecamatan');
            $table->index('reporter_phone');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
