<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeyAndDropColumnMeInteresa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('me_interesa', function (Blueprint $table) {
            $table->dropForeign(['co_inversionista']);
            $table->dropColumn('co_inversionista');
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
