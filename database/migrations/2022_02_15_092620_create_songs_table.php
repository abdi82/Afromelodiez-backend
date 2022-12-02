<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('name',1000)->nullable();
            $table->string('song',1000)->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->on('categories')->references('id')->onUpdate('cascade')->nullOnDelete();
            $table->unsignedInteger('artist_id')->nullable();
            $table->foreign('artist_id')->on('artist')->references('id')->onUpdate('cascade')->nullOnDelete();
            $table->unsignedInteger('language_id')->nullable();
            $table->foreign('language_id')->on('languages')->references('id')->onUpdate('cascade')->nullOnDelete();
            $table->string('song_image');
            $table->unsignedInteger('album')->nullable();
            $table->foreign('album')->references('id')->on('albums')->onUpdate('cascade')->nullOnDelete();
            $table->integer('played')->nullable();
            $table->string('lyrics',1000)->nullable();
            $table->longText('liked')->nullable();
            $table->date('release_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
}
