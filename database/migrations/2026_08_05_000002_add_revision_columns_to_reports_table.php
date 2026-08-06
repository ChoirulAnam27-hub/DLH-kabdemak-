<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tambah Kolom Revisi pada Tabel Reports
 * 
 * Menambahkan:
 * - sla_due_at: Tenggat waktu SLA penanganan (default +24 jam)
 * - is_anonymous: Flag pelapor anonim
 * - rejection_reason: Alasan penolakan laporan
 * - kecamatan_id: FK ke tabel kecamatans (master wilayah)
 * - desa_id: FK ke tabel desas (master wilayah)
 * 
 * Kolom string 'kecamatan' lama dipertahankan untuk backward compatibility.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->timestamp('sla_due_at')->nullable()->after('resolved_at')
                  ->comment('Tenggat SLA, default +24 jam dari waktu laporan masuk');
            $table->boolean('is_anonymous')->default(false)->after('reporter_email')
                  ->comment('True jika pelapor ingin identitasnya dirahasiakan');
            $table->text('rejection_reason')->nullable()->after('resolution_notes')
                  ->comment('Wajib diisi saat status diubah ke ditolak');
            $table->foreignId('kecamatan_id')->nullable()->after('kecamatan')
                  ->constrained('kecamatans')->onDelete('set null');
            $table->foreignId('desa_id')->nullable()->after('kecamatan_id')
                  ->constrained('desas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropForeign(['kecamatan_id']);
            $table->dropColumn(['sla_due_at', 'is_anonymous', 'rejection_reason', 'kecamatan_id', 'desa_id']);
        });
    }
};
