<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('pemakaians', function (Blueprint $table) {
        $table->dateTime('tanggal_bayar')->nullable();
    });
}

public function down()
{
    Schema::table('pemakaians', function (Blueprint $table) {
        $table->dropColumn('tanggal_bayar');
    });
}

};
