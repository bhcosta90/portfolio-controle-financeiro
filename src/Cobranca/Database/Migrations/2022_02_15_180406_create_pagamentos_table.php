<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Cobranca\Models\Pagamento;

class CreatePagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tenant_id')->index();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('cobranca_id')->nullable()->constrained('cobrancas');
            $table->foreignId('conta_bancaria_id')->constrained('conta_bancarias');
            $table->foreignId('forma_pagamento_id')->constrained('forma_pagamentos');
            $table->foreignId('entidade_id')->nullable()->constrained('entidades');
            $table->string('pagamento_type');
            $table->string('movimento');
            $table->string('descricao')->nullable();
            $table->unsignedTinyInteger('parcela')->nullable();
            $table->enum('tipo', array_keys(Pagamento::getTipoFormatarAttribute()));
            $table->double('saldo_anterior');
            $table->double('saldo_atual');
            $table->unsignedDouble('valor_cobranca');
            $table->unsignedDouble('valor_multa');
            $table->unsignedDouble('valor_juros');
            $table->unsignedDouble('valor_desconto');
            $table->double('valor_total');
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
        Schema::dropIfExists('pagamentos');
    }
}
