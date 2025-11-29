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
        Schema::create('barangmasuks', function (Blueprint $table) {
            $table->id();
            $table->string('id_barangmasuk');
            $table->unsignedBigInteger('id_tenant');
            $table->date('tanggal');
            $table->unsignedBigInteger('id_databarang');
            $table->integer('jumlah_masuk');
            $table->timestamps();

            // Composite unique key: id_tenant + id_barangmasuk
            $table->unique(['id_tenant', 'id_barangmasuk']);

            // Foreign keys
            $table->foreign('id_tenant')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_databarang')->references('id')->on('databarangs')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangmasuks');
    }
};
