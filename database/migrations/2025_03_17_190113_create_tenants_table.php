<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); 
            $table->string('nama_perusahaan'); // Menambahkan nama perusahaan
            $table->string('kode')->unique(); 
            $table->string('email')->unique(); 
            $table->string('telepon')->nullable(); 
            $table->text('alamat')->nullable(); 
            $table->enum('status', ['pending', 'aktif', 'nonaktif'])->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
