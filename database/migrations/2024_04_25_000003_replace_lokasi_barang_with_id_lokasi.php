<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Drop the old column if it exists
            if (Schema::hasColumn('items', 'lokasi_barang')) {
                $table->dropColumn('lokasi_barang');
            }

            // Add the new column if it doesn't exist
            if (!Schema::hasColumn('items', 'id_lokasi')) {
                $table->foreignId('id_lokasi')->nullable()->constrained('locations');
            }
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('lokasi_barang')->nullable();
            $table->dropForeign(['id_lokasi']);
            $table->dropColumn('id_lokasi');
        });
    }
};
