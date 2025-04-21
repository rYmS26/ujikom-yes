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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->string('NoKontrol')->unique();
            $table->string('nama', 100);
            $table->string('alamat', 255);
            $table->string('telepon', 15);
            $table->string('jenis_plg', 50);
            $table->foreign('jenis_plg')->references('id_jenis')->on('jenis_plg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
