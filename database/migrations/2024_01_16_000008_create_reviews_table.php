<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_item')->constrained('items')->onDelete('cascade');
                $table->integer('rating');
                $table->text('komentar');
                $table->timestamps();
            });
        } else {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'id_item')) {
                    $table->foreignId('id_item')->constrained('items')->onDelete('cascade');
                }
                if (!Schema::hasColumn('reviews', 'rating')) {
                    $table->integer('rating');
                }
                if (!Schema::hasColumn('reviews', 'komentar')) {
                    $table->text('komentar');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
