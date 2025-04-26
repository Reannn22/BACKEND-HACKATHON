<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop the existing foreign key if it exists
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropForeign(['id_lokasi']);
            });
        }

        // Drop the existing locations table
        Schema::dropIfExists('locations');

        // Create new locations table
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
        });

        // Add default location
        DB::table('locations')->insert([
            'nama_lokasi' => 'Default Location'
        ]);

        // Restore foreign key
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                $table->foreign('id_lokasi')->references('id')->on('locations');
            });

            // Update existing items to use default location
            DB::table('items')->update(['id_lokasi' => 1]);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // No down migration needed since this is a cleanup migration
    }
};
