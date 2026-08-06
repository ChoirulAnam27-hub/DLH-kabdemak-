<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Foto Laporan
 * 
 * Menyimpan foto bukti pengaduan (dari warga) dan 
 * foto bukti penyelesaian (dari petugas).
 * Setiap laporan bisa memiliki beberapa foto.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->string('photo_path')->comment('Path relatif file foto di storage');
            $table->enum('type', ['bukti', 'penyelesaian'])->default('bukti');
            $table->string('caption', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_photos');
    }
};
