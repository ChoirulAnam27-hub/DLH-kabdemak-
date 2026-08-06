<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Kategori Pengaduan
 * 
 * Menyimpan jenis-jenis pengaduan lingkungan:
 * Sampah Menumpuk, Pencemaran Air, Pencemaran Udara, Limbah B3, dll.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('icon', 50)->default('bi-exclamation-triangle');
            $table->string('color', 20)->default('#dc3545');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
