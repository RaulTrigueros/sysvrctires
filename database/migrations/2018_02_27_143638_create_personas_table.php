<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 20)->unique()->nullable();
            $table->string('nombre', 120);
            $table->string('dui', 10)->nullable();
            $table->string('nit', 17)->nullable();
            $table->string('nrc', 17)->nullable();
            $table->string('giro', 120)->nullable();
            $table->string('direccion', 250)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->timestamps();
        });
        DB::table('personas')->insert(array('id' => 1, 'nombre' => 'administrador'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
