<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('jenis_plg', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment
            $table->string('id_jenis', 50)->unique(); // Tambahkan constraint unique
            $table->string('nama_jenis', 100);
            $table->decimal('biayabeban', 10, 2);
            $table->decimal('tarifkwh', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_plg');
    }
};
