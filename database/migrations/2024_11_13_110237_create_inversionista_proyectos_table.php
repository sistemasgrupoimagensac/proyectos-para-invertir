<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInversionistaProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inversionista_proyectos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('prestamo_id')->index();
            $table->integer('persona_id')->index();
            $table->integer('prioridad')->unsigned();
            $table->integer('estado')->unsigned();
            $table->integer('user_modifica')->unsigned()->nullable()->index();

            $table->foreign('prestamo_id')->references('co_prestamo')->on('p_prestamo')->onDelete('cascade');
            $table->foreign('persona_id')->references('co_persona')->on('p_persona')->onDelete('cascade');

            $table->unique(['prestamo_id', 'prioridad']);
            
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
        Schema::dropIfExists('inversionista_proyectos');
    }
}
