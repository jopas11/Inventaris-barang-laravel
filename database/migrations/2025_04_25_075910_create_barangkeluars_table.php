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
        Schema::create('barangkeluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_barangkeluar');
            $table->unsignedBigInteger('id_tenant');
            $table->date('tanggal'); // Add tanggal
            $table->unsignedBigInteger('id_databarang'); // Foreign key for databarang
            $table->integer('jumlah_keluar'); // Add jumlah masuk
            $table->timestamps();

            $table->unique(['id_tenant', 'no_barangkeluar']);


            // Foreign key constraint
            $table->foreign('id_tenant')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_databarang')->references('id')->on('databarangs')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangkeluars');
    }
};
