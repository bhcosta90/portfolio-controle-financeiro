<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Cobranca\Models\Cobranca;

class CreateCobrancasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cobrancas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tenant_id')->index();
            $table->foreignId('entidade_id')->nullable()->constrained('entidades');
            $table->foreignId('frequencia_id')->nullable()->constrained('frequencias');
            $table->foreignId('forma_pagamento_id')->constrained('forma_pagamentos');
            $table->foreignId('conta_bancaria_id')->nullable()->constrained('conta_bancarias');
            $table->morphs('cobranca');
            $table->enum('tipo', array_keys(Cobranca::getTipoFormatarAttribute()));
            $table->string('descricao')->nullable();
            $table->unsignedDouble('valor_cobranca');
            $table->unsignedDouble('valor_frequencia')->nullable();
            $table->unsignedDouble('valor_original')->nullable();
            $table->text('observacao')->nullable();
            $table->date('data_emissao');
            $table->date('data_original');
            $table->date('data_vencimento');
            $table->unsignedTinyInteger('parcela')->nullable();
            $table->enum('status', array_keys(Cobranca::getTextStatusAttribute()));
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
        Schema::dropIfExists('cobrancas');
    }
}
