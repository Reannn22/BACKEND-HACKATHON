<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Store existing location IDs and names
        $locations = DB::table('locations')->select('id', 'nama_lokasi')->get();

        // Drop and recreate the table
        Schema::dropIfExists('locations');
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
        });

        // Restore location data
        foreach ($locations as $location) {
            DB::table('locations')->insert([
                'id' => $location->id,
                'nama_lokasi' => $location->nama_lokasi
            ]);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // No need for down migration as this is a cleanup
    }
};
