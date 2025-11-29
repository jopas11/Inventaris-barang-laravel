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
        Schema::create('databarangs', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang');
            $table->string('nama_barang');
            $table->integer('stok');
            $table->unsignedBigInteger('id_satuan');
            $table->unsignedBigInteger('id_jenisbarang');
            $table->unsignedBigInteger('id_tenant');
            $table->string('lokasi'); // Tambahkan kolom lokasi di sini
            $table->timestamps();

            $table->foreign('id_satuan')->references('id')->on('satuans')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_jenisbarang')->references('id')->on('jenisbarangs')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_tenant')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('restrict');

            // Composite unique key: id_tenant + id_barang
            $table->unique(['id_tenant', 'id_barang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('databarangs');
    }
};
