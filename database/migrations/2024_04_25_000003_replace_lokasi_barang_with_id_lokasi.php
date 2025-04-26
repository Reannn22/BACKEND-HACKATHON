<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Drop the foreign key first if exists
            if (Schema::hasColumn('items', 'id_lokasi')) {
                $table->dropForeign(['id_lokasi']);
                $table->dropColumn('id_lokasi');
            }

            // Add the new column with foreign key
            $table->foreignId('id_lokasi')
                  ->after('id_kategori')
                  ->constrained('locations');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['id_lokasi']);
            $table->dropColumn('id_lokasi');
        });
    }
};
