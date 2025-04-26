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

        // Store existing relationships
        $itemLocations = DB::table('items')->select('id', 'id_lokasi')->get();

        // Store location names
        $existingLocations = DB::table('locations')->select('id', 'nama_lokasi')->get();

        // Drop and recreate locations table
        Schema::dropIfExists('locations');
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
        });

        // Restore locations data
        foreach ($existingLocations as $location) {
            DB::table('locations')->insert([
                'id' => $location->id,
                'nama_lokasi' => $location->nama_lokasi
            ]);
        }

        // Update items relationships
        foreach ($itemLocations as $item) {
            DB::table('items')
                ->where('id', $item->id)
                ->update(['id_lokasi' => $item->id_lokasi]);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // No down migration needed
    }
};
