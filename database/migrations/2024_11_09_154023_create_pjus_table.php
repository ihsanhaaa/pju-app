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
        Schema::create('pjus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kwh_id')->nullable();
            $table->foreignId('kecamatan_id')->nullable();
            $table->string('nama_pju')->nullable();
            $table->string('zona')->nullable();
            $table->string('kelompok')->nullable();
            $table->string('kategori')->nullable();
            $table->string('nomor_seri')->nullable();
            $table->boolean('connected_to_kwh')->default(false);
            $table->unsignedBigInteger('connected_to_pju')->nullable();
            $table->json('geojson');
            $table->timestamps();
            $table->foreign('connected_to_pju')->references('id')->on('pjus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pjus');
    }
};
