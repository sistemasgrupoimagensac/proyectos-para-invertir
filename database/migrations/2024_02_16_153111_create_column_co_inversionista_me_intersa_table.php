<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnCoInversionistaMeIntersaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('me_interesa', function (Blueprint $table) {
            $table->unsignedBigInteger('co_inversionista')->nullable();
            $table->foreign('co_inversionista')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('me_interesa', function (Blueprint $table) {
            //
        });
    }
}
