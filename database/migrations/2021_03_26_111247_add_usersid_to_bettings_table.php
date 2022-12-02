<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsersidToBettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bettings', function (Blueprint $table) {
            $table->integer('userid_1')->nullable();
            $table->integer('userid_2')->nullable();
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
            $table->dropColumn('userid_1');
            $table->dropColumn('userid_2');
        });
    }
}
