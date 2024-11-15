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
        Schema::create('foto_kwhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kwh_id')->constrained('kwhs')->onDelete('cascade'); 
            $table->string('path_foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_kwhs');
    }
};
