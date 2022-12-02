<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bettings', function (Blueprint $table) {
            $table->id();
            $table->string('video_link1')->nullable();
            $table->string('video_link2')->nullable();
            $table->string('emoji_1')->nullable();
            $table->string('emoji_2')->nullable();
            $table->string('tag_1')->nullable();
            $table->string('tag_2')->nullable();
            $table->string('time');
            $table->string('money');
            $table->string('member_qty');
            $table->integer('category_id')->nullable();
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
        Schema::dropIfExists('bettings');
    }
}
