<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('foto_ulasans', function (Blueprint $table) {
            $table->uuid('unique_id')->after('id')->unique();
        });
    }

    public function down()
    {
        Schema::table('foto_ulasans', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });
    }
};
