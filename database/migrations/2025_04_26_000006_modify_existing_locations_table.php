<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key checks and get all items
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Store existing relationships
        $items = DB::table('items')->select('id', 'id_lokasi')->get();

        // Drop the old table
        Schema::dropIfExists('locations');

        // Create new simplified table
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');
        });

        // Add default location if needed
        DB::table('locations')->insert([
            'nama_lokasi' => 'Default Location'
        ]);

        // Update items with default location
        foreach ($items as $item) {
            DB::table('items')
                ->where('id', $item->id)
                ->update(['id_lokasi' => 1]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // No need for down migration as we're simplifying the table
    }
};
