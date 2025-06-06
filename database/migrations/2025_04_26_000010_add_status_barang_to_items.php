<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'status_barang')) {
                $table->enum('status_barang', ['aktif', 'non-aktif', 'dipinjam', 'dalam perbaikan'])
                      ->after('kondisi_barang')
                      ->default('aktif');
            }
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('status_barang');
        });
    }
};
