<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Master Kecamatan & Desa
 * 
 * Menyimpan data wilayah administratif Kabupaten Demak
 * untuk digunakan sebagai dependent dropdown pada form pelaporan
 * dan filter data di dashboard admin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->timestamps();
        });

        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->string('name', 100);
            $table->timestamps();

            $table->unique(['kecamatan_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desas');
        Schema::dropIfExists('kecamatans');
    }
};
