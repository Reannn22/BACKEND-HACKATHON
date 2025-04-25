<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Reset foto_ulasans table
        DB::statement('TRUNCATE TABLE foto_ulasans');

        // Reset auto increment to 0
        DB::statement('ALTER TABLE foto_ulasans AUTO_INCREMENT = 1');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // No need for down method as this is a one-time reset
    }
};
