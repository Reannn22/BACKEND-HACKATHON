<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('berat_barang')->change();
            $table->string('is_dibawa')->change();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('berat_barang', 10, 2)->change();
            $table->boolean('is_dibawa')->change();
        });
    }
};
