<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movie_halls', function (Blueprint $table) {
            $table->increments('id_movie_hall');
            $table->timestamp('date_published');
            $table->timestamp('date_finished')->default('1970-01-01 00:00:00');
            $table->integer('id_movie')->unsigned();
            $table->integer('id_hall')->unsigned()->nullable();
            $table->foreign('id_movie')->references('id_movie')->on('movies');
            $table->foreign('id_hall')->references('id_hall')->on('halls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_halls');
    }
};
