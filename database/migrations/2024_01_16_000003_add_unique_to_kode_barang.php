<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Remove duplicates first by keeping only the latest entry for each kode_barang
        $duplicates = DB::table('items')
            ->select('kode_barang')
            ->groupBy('kode_barang')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $latestId = DB::table('items')
                ->where('kode_barang', $duplicate->kode_barang)
                ->latest()
                ->first()->id;

            DB::table('items')
                ->where('kode_barang', $duplicate->kode_barang)
                ->where('id', '!=', $latestId)
                ->delete();
        }

        // Now add the unique constraint
        Schema::table('items', function (Blueprint $table) {
            $table->unique('kode_barang');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropUnique(['kode_barang']);
        });
    }
};
