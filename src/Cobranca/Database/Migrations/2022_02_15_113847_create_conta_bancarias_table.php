<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Cobranca\Models\ContaBancaria;

class CreateContaBancariasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conta_bancarias', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tenant_id')->index();
            $table->foreignId('banco_id')->constrained('bancos');
            $table->string('agencia');
            $table->string('conta');
            $table->enum('tipo', array_keys(ContaBancaria::getTipoAttribute()))->nullable();
            $table->enum('tipo_documento', array_keys(ContaBancaria::getTipoDocumentoAttribute()))->nullable();
            $table->string('documento');
            $table->double('valor')->default(0);
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
        Schema::dropIfExists('conta_bancarias');
    }
}
