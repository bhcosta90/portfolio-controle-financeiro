<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Entidade\Models\Banco;
use Modules\Entidade\Models\Entidade;

class CreateEntidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entidades', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('tenant_id')->index();
            $table->morphs('entidade');
            $table->string('nome');
            $table->enum('tipo', array_keys(Entidade::getTipoAttribute()))->nullable();
            $table->string('documento')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('endereco')->nullable();
            $table->text('observacao')->nullable();
            $table->foreignId('banco_id', 10)->nullable()->constrained('entidades');
            $table->enum('banco_codigo', array_keys(Banco::getBancoCodigoAttribute()))->nullable();
            $table->string('banco_agencia', 20)->nullable();
            $table->string('banco_conta', 30)->nullable();
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
        Schema::dropIfExists('entidades');
    }
}
