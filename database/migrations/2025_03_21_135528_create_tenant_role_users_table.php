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
        Schema::create('tenant_role_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tenant');
            $table->unsignedBigInteger('id_role');
            $table->foreign('id_tenant')->references('id')->on('tenants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_role')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_role_users');
    }
};
