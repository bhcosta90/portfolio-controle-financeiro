<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrquenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frequencias', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tenant_id')->index();
            $table->string('tipo');
            $table->string('nome');
            $table->tinyInteger('ordem_frequencia');
            $table->tinyInteger('ordem_parcela');
            $table->boolean('ativo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frquencias');
    }
}
