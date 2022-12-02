<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnOnUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            //
            $table->boolean('dataSaver')->default(0)->change();
            $table->boolean('downloadOnly')->default(0)->change();
            $table->boolean('StreamOnly')->default(0)->change();
            $table->string('crossfade')->default(0)->change();
            $table->boolean('gapless')->default(0)->change();
            $table->boolean('automix')->default(0)->change();
            $table->boolean('autoplay')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            //
        });
    }
}
