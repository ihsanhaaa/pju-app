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
        Schema::create('kwhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->nullable();
            $table->string('nama_kwh')->nullable();
            $table->string('id_pelanggan')->nullable();
            $table->string('jenis_kwh')->nullable();
            $table->string('kategori_perangkat')->nullable();
            $table->json('geojson');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwhs');
    }
};
