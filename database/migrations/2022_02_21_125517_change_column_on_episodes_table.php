<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnOnEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('podcasts_episodes', function (Blueprint $table) {
            $table->string('episode',1000)->change();
            $table->string('podcast',1000)->nullable()->change();
            $table->string('title',1000)->nullable()->change();
            $table->string('description',3000)->nullable()->change();
            $table->string('image',1000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('featured_episodes', function (Blueprint $table) {
            //
        });
    }
}
