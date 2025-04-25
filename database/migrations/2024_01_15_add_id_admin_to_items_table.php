<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('items', 'id_admin')) {
            Schema::table('items', function (Blueprint $table) {
                $table->foreignId('id_admin')->nullable()->after('is_dibawa');
            });

            // Update existing records with a default admin ID
            DB::table('items')->update(['id_admin' => 1]);

            Schema::table('items', function (Blueprint $table) {
                $table->foreign('id_admin')->references('id')->on('users');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('items', 'id_admin')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropForeign(['id_admin']);
                $table->dropColumn('id_admin');
            });
        }
    }
};
