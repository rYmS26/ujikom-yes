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
        Schema::create('tarif_log', function (Blueprint $table) {
            $table->id();
            $table->string('id_jenis', 50);
            $table->decimal('tarifkwh', 10, 2);
            $table->decimal('biayabeban', 10, 2);
            $table->date('berlaku_mulai');
            $table->date('berlaku_sampai')->nullable();
            $table->timestamps();

            $table->foreign('id_jenis')->references('id_jenis')->on('jenis_plg')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_log');
    }
};
