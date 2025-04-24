<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->string('no_hp')->nullable();
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_token_expiry')->nullable();
            $table->string('phone_change_token')->nullable();
            $table->timestamp('phone_change_token_expiry')->nullable();
            $table->string('password_change_token')->nullable();
            $table->timestamp('password_change_token_expiry')->nullable();
            $table->timestamps();
        });

        // Set auto-increment to start from 0
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 0');
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
