<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaptionToBettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bettings', function (Blueprint $table) {
            $table->string('caption_1');
            $table->string('caption_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bettings', function (Blueprint $table) {
            $table->dropColumn('caption_1');
            $table->dropColumn('caption_2');
        });
    }
}
