<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('lokasi_barang');
            $table->foreignId('id_lokasi')->nullable()->after('id_kategori')->constrained('locations');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['id_lokasi']);
            $table->dropColumn('id_lokasi');
            $table->string('lokasi_barang')->nullable();
        });
    }
};
