<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Costa\LaravelPackage\Utils\Value;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Cobranca\Models\Cobranca;
use Modules\Cobranca\Models\ContaBancaria;
use Modules\Entidade\Models\Banco;
use Modules\Entidade\Models\Entidade;

class ContatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenant::create([
            'id' => 'testecontato',
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'Contato de Teste',
            'email' => 'contato@noreply.com',
            'tenant_id' => $tenant->id,
            'password' => '$2y$10$iPROZXJucKKgUfuEkBqrTOf2aG4.ytR9c58MyEasXDk6Zn9HUXdy2' // 123456
        ]);
        $user->default();

        $this->cadastrarBanco($user);
        $this->cadastrarContaBancaria($user);
    }

    protected function cadastrarBanco(User $user)
    {
        if ($user) {
            $datas = [
                ['nome' => 'Nu Pagamentos S.A.', 'banco_codigo' => '260', 'documento' => '18.236.120/0001-58'],
                ['nome' => 'Picpay Servicos S.A.', 'banco_codigo' => '380', 'documento' => '22.896.431/0001-10'],
            ];

            foreach ($datas as $data) {
                $objBanco = Banco::create([]);

                $data += [
                    'uuid' => (string) str()->uuid(),
                    'tenant_id' => (string) $user->tenant_id,
                    'ativo' => true,
                    'entidade_type' => get_class($objBanco),
                    'entidade_id' => $objBanco->id,
                    'tipo' => Entidade::$TIPO_PJ
                ];

                DB::table('entidades')->insert($data);
            }
        }
    }

    protected function cadastrarContaBancaria(User $user)
    {
        $datas = [
            ['agencia' => '0001', 'conta' => '0000000-0', 'entidade_id' => 'Nu Pagamentos S.A.'],
            ['agencia' => '0001', 'conta' => '000000-00-0', 'entidade_id' => 'Picpay Servicos S.A.'],
        ];

        foreach ($datas as $data) {
            $data += [
                'uuid' => (string) str()->uuid(),
                'tenant_id' => (string) $user->tenant_id,
                'tipo' => ContaBancaria::$TIPO_CC,
                'tipo_documento' => ContaBancaria::$TIPO_DOCUMENTO_PF,
                'documento' => '000.000.000-00',
                'ativo' => true,
            ];
            $bancoId = Entidade::whereNome($data['entidade_id'])->whereEntidadeType(Banco::class)->whereTenantId($user->tenant_id)->firstOrFail()->id;
            $data['entidade_id'] = $bancoId;

            DB::table('conta_bancarias')->insert($data);
        }
    }
}
