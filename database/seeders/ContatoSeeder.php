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
            'email' => 'contato@norelly.com',
            'tenant_id' => $tenant->id,
            'password' => '$2y$10$gaWJdbSSzQY4bF76qRp6auMh0Sy1N6Qq7TuMo9eLSewCXKDf34r9C'
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
            ['agencia' => '0001', 'conta' => '0000000-0', 'banco_id' => 'Nu Pagamentos S.A.'],
            ['agencia' => '0001', 'conta' => '000000-00-0', 'banco_id' => 'Picpay Servicos S.A.'],
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
            $bancoId = Entidade::whereNome($data['banco_id'])->whereEntidadeType(Banco::class)->whereTenantId($user->tenant_id)->firstOrFail()->id;
            $data['banco_id'] = $bancoId;

            DB::table('conta_bancarias')->insert($data);
        }
    }

    protected function registrarCobrancas($class, $idTenant, $idEntidade, $idFormaPagamento, $idFrequencia, $data){
        if (empty($data['parcela_total'])) {
            $obj = app($class)->create([]);
            try {
                Cobranca::create([
                    'forma_pagamento_id' => $idFormaPagamento,
                    'tenant_id' => $idTenant,
                    'cobranca_type' => get_class($obj),
                    'cobranca_id' => $obj->id,
                    'valor_cobranca' => $data['valor'],
                    'entidade_id' => $idEntidade,
                    'data_emissao' => $data['data_vencimento'],
                    'data_original' => $data['data_vencimento'],
                    'data_vencimento' => $data['data_vencimento'],
                    'observacao' => $data['observacao'] ?? null,
                    'descricao' => $data['descricao'] ?? null,
                    'frequencia_id' => empty($data['type']) ? null : $idFrequencia,
                ]);
            } catch(Exception $e){
                dump($class, $idTenant, $idEntidade, $idFrequencia, $data);
                throw $e;
            }
        } else {
            $values = (new Value())->parcel(new Carbon($data['data_vencimento']), $data['valor'], $data['parcela_total']);
            foreach($values as $i => $rs){
                $obj = app($class)->create([]);

                Cobranca::create([
                    'forma_pagamento_id' => $idFormaPagamento,
                    'tenant_id' => $idTenant,
                    'cobranca_type' => get_class($obj),
                    'cobranca_id' => $obj->id,
                    'descricao' => $data['descricao'],
                    'valor_cobranca' => $rs['value'],
                    'entidade_id' => $idEntidade,
                    'data_emissao' => $data['data_vencimento'],
                    'data_original' => $data['data_vencimento'],
                    'data_vencimento' => $rs['due_date'],
                    'parcela' => $i + 1,
                ]);
            }
        }
    }

    protected function income()
    {
        return [
            [
                'nome' => 'Jair da Costa',
                'descricao' => 'Mensalidade do celular',
                'parcela_total' => 10,
                'valor' => 799,
                'data_vencimento' => '2021-08-20'
            ],
            [
                'nome' => 'Jair da Costa',
                'descricao' => 'Mensalidade do Seguro para o celular',
                'parcela_total' => 12,
                'valor' => 235.92,
                'data_vencimento' => '2021-08-20'
            ],
            [
                'nome' => 'José Maria da Costa',
                'descricao' => 'Empréstimo do cartão de crédito',
                'valor' => 573.62,
                'data_vencimento' => '2021-02-05',
                'observacao' => 'Com desconto das duas parcelas do armário de quarto (Valor do Cartão = 1065 | Valor da Faculdade = 308.62 | Parcela do armário = -800)',
            ],
            [
                'nome' => 'José Maria da Costa',
                'descricao' => 'Empréstimo para funerária',
                'valor' => 1750.00,
                'data_vencimento' => '2021-02-05',
            ],
            [
                'nome' => 'PJBank',
                'descricao' => 'Vale do pagamento',
                'valor' => 1893.27,
                'data_vencimento' => (new Carbon())->setDay('10')->format('Y-m-d'),
                'type' => 'every_20th',
            ],
            [
                'nome' => 'PJBank',
                'descricao' => 'Pagamento',
                'valor' => 2628.81,
                'data_vencimento' => (new Carbon())->setDay('10')->format('Y-m-d'),
                'type' => 'fifth_business_day',
            ],
            [
                "nome" => "Victória Costa",
                'descricao' => 'Diaria da cuidadora',
                'valor' => 100,
                "data_vencimento" => "2021-12-28",
            ],
            [
                "nome" => "Victória Costa",
                'descricao' => 'Fogão',
                'valor' => 150,
                "data_vencimento" => "2021-12-28",
            ],
            [
                'nome' => 'Paulo Afonso',
                'descricao' => 'Televisão',
                'valor' => 35,
                'type' => 'fifth_business_day',
                'data_vencimento' => '2021-10-01',
            ],
            [
                'nome' => 'José Maria da Costa',
                'descricao' => 'Internet descontando o netflix',
                'valor' => 97.05,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon())->setDay('10')->format('Y-m-d'),
            ]

        ];
    }

    protected function costs()
    {
        return [
            [
                'nome' => 'Podóloga',
                'descricao' => 'Corte de unha do Bruno Henrique da Costa',
                'descricao' => 'Pagamento via PIX',
                'type' => 'month',
                'valor' => 80,
                'data_vencimento' => (new Carbon())->setDay('10')->format('Y-m-d'),
            ],
            [
                'nome' => 'André Trevisan',
                'descricao' => 'Aula de violino',
                'valor' => 120,
                "data_vencimento" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'every_20th',
            ],
            [
                'nome' => 'CPFL',
                'descricao' => 'Força',
                'valor' => 150,
                "data_vencimento" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'fifth_business_day',
            ],
            [
                'nome' => 'DAE',
                'descricao' => 'Água',
                'valor' => 50,
                "data_vencimento" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'fifth_business_day',
            ],
            [
                'nome' => 'Rosângela Macário de Lima',
                'descricao' => 'Televisão',
                'valor' => 35 * 2,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Transferência via PIX: (19) 99181-7970 (Referente a televisão do Bruno, Paulo Afonso)',
            ],
            [
                'nome' => 'Faculdade Einstein Limeira',
                'descricao' => 'Faculdade Mayara',
                'valor' => 648,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Pagamento via boleto',
            ],
            [
                'nome' => 'Comgás',
                'descricao' => 'Gás do apartamento',
                'valor' => 5.00,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Pagamento via aplicativo',
            ],
            [
                'nome' => 'Caixa Economica Federal',
                'descricao' => 'Apartamento',
                'valor' => 595,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Pagamento do NuBank para a Caixa',
            ],
            [
                'nome' => 'Canção Nova',
                'descricao' => 'Doação pela Josefina Costa',
                'valor' => 50,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Pagamento via PIX',
            ],
            [
                'nome' => 'Josefina Costa',
                'descricao' => 'Faxina',
                'valor' => 150,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
            [
                'nome' => 'Josefina Costa',
                'descricao' => 'Faculdade',
                'valor' => 200,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
            [
                'nome' => 'Vivo',
                'descricao' => 'Internet - Bruno Costa',
                'valor' => 139.99,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
            [
                'nome' => 'Vivo',
                'descricao' => 'Internet - José Maria da Costa',
                'valor' => 119.99,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
            ],
            [
                'nome' => 'Nubank',
                'descricao' => 'Cartão de crédito',
                'valor' => 2000,
                'type' => 'every_20th',
                'data_vencimento' => (new Carbon())->setDay('10')->format('Y-m-d'),
            ]
        ];
    }
}
