<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('userID');
            $table->boolean('dataSaver');
            $table->boolean('downloadOnly');
            $table->boolean('StreamOnly');
            $table->string('crossfade');
            $table->boolean('gapless');
            $table->boolean('automix');
            $table->boolean('autoplay');
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
        Schema::dropIfExists('user_settings');
    }
}
