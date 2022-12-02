<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialColumnsOnArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artists', function (Blueprint $table) {
            //
            $table->string('facebook',1000)->nullable();
            $table->string('twitter',1000)->nullable();
            $table->string('youtube',1000)->nullable();
            $table->string('instagram',1000)->nullable();
            $table->string('snapchat',1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('artists', function (Blueprint $table) {
            //
        });
    }
}
