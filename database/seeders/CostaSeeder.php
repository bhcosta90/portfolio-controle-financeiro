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
use Modules\Cobranca\Models\ContaPagar;
use Modules\Cobranca\Models\ContaReceber;
use Modules\Cobranca\Models\FormaPagamento;
use Modules\Cobranca\Models\Frequencia;
use Modules\Entidade\Models\Banco;
use Modules\Entidade\Models\Cliente;
use Modules\Entidade\Models\Entidade;
use Modules\Entidade\Models\Fornecedor;

class CostaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenant = Tenant::create([
            'id' => 'bhcosta90',
        ]);

        $user = \App\Models\User::factory()->create([
            'name' => 'Bruno Henrique da Costa',
            'email' => 'bhcosta90@gmail.com',
            'tenant_id' => $tenant->id,
            'password' => '$2y$10$gaWJdbSSzQY4bF76qRp6auMh0Sy1N6Qq7TuMo9eLSewCXKDf34r9C'
        ]);
        $user->default();

        \App\Models\User::factory()->create([
            'name' => 'Mayara Thaine de Carvalho',
            'email' => 'mayarathc99@gmail.com',
            'tenant_id' => $tenant->id,
            'password' => '$2y$10$gaWJdbSSzQY4bF76qRp6auMh0Sy1N6Qq7TuMo9eLSewCXKDf34r9C'
        ]);


        $idFrequencia = Frequencia::whereTipo('30|days')->whereTenantId($user->tenant->id)->first()->id;
        $idFormaPagamento = FormaPagamento::whereNome('Dinheiro')->whereTenantId($user->tenant->id)->first()->id;

        $this->cadastrarBanco($user);
        $this->cadastrarContaBancaria($user);

        $clientes = [];
        foreach ($this->income() as $rs) {
            if (empty($clientes[$nomeCliente = $rs['nome']])) {
                $obj = Cliente::create([]);
                $clientes[$nomeCliente] = Entidade::create([
                    'nome' => $nomeCliente,
                    'tenant_id' => $user->tenant_id,
                    "entidade_id" => $obj->id,
                    "entidade_type" => get_class($obj),
                    'ativo' => true,
                ]);
            }

            $this->registrarCobrancas(ContaReceber::class, $user->tenant->id, $clientes[$nomeCliente]->id, $idFrequencia, $rs);
        }

        $fornecedores = [];
        foreach ($this->costs() as $rs) {
            if (empty($fornecedores[$nomeCliente = $rs['nome']])) {
                $obj = Fornecedor::create([]);
                $fornecedores[$nomeCliente] = Entidade::create([
                    'nome' => $nomeCliente,
                    'tenant_id' => $user->tenant_id,
                    "entidade_id" => $obj->id,
                    "entidade_type" => get_class($obj),
                    'ativo' => true,
                ]);
            }

            $this->registrarCobrancas(ContaPagar::class, $user->tenant->id, $fornecedores[$nomeCliente]->id, $idFrequencia, $rs);
        }

        Cobranca::where('parcela', '<=', 6)
            ->where('entidade_id', $clientes['Jair da Costa']->id)
            ->update([
                'deleted_at' => Carbon::now(),
            ]);

        Cobranca::where('parcela', '>', 6)
            ->where('entidade_id', $clientes['Jair da Costa']->id)
            ->update([
                'entidade_id' => $clientes['José Maria da Costa']->id,
            ]);
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
            ['agencia' => '0001', 'conta' => '9954491-3', 'entidade_id' => 'Nu Pagamentos S.A.'],
            ['agencia' => '0001', 'conta' => '141419-87-3', 'entidade_id' => 'Picpay Servicos S.A.'],
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

    protected function registrarCobrancas($class, $idTenant, $idEntidade, $idFrequencia, $data)
    {
        if (empty($data['parcela_total'])) {
            $obj = app($class)->create([]);
            try {
                Cobranca::create([
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
                    'tipo' => $data['tipo'],
                ]);
            } catch (Exception $e) {
                dump($class, $idTenant, $idEntidade, $idFrequencia, $data);
                throw $e;
            }
        } else {
            $values = (new Value())->parcel(new Carbon($data['data_vencimento']), $data['valor'], $data['parcela_total']);
            foreach ($values as $i => $rs) {
                $obj = app($class)->create([]);

                Cobranca::create([
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
                    'tipo' => $data['tipo'],
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
                'data_vencimento' => '2021-08-20',
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                'nome' => 'Jair da Costa',
                'descricao' => 'Mensalidade do Seguro para o celular',
                'parcela_total' => 12,
                'valor' => 235.92,
                'data_vencimento' => '2021-08-20',
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                'nome' => 'José Maria da Costa',
                'descricao' => 'Empréstimo do cartão de crédito',
                'valor' => 573.62,
                'data_vencimento' => '2021-02-05',
                'observacao' => 'Com desconto das duas parcelas do armário de quarto (Valor do Cartão = 1065 | Valor da Faculdade = 308.62 | Parcela do armário = -800)',
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                'nome' => 'José Maria da Costa',
                'descricao' => 'Empréstimo para funerária',
                'valor' => 1750.00,
                'data_vencimento' => '2021-02-05',
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                'nome' => 'PJBank',
                'descricao' => 'Vale do pagamento',
                'valor' => 1893.27,
                'data_vencimento' => (new Carbon)->setDay('10')->format('Y-m-d'),
                'type' => 'every_20th',
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                'nome' => 'PJBank',
                'descricao' => 'Pagamento',
                'valor' => 2628.81,
                'data_vencimento' => (new Carbon)->setDay('10')->format('Y-m-d'),
                'type' => 'fifth_business_day',
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                "nome" => "Victória Costa",
                'descricao' => 'Diaria da cuidadora',
                'valor' => 100,
                "data_vencimento" => "2021-12-28",
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                "nome" => "Victória Costa",
                'descricao' => 'Fogão',
                'valor' => 150,
                "data_vencimento" => "2021-12-28",
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                'nome' => 'Paulo Afonso',
                'descricao' => 'Televisão',
                'valor' => 35,
                'type' => 'fifth_business_day',
                'data_vencimento' => '2021-10-01',
                'tipo' => Cobranca::$TIPO_CREDITO,
            ],
            [
                'nome' => 'José Maria da Costa',
                'descricao' => 'Internet descontando o netflix',
                'valor' => 97.05,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay('10')->format('Y-m-d'),
                'tipo' => Cobranca::$TIPO_CREDITO,
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
                'data_vencimento' => (new Carbon)->setDay('10')->format('Y-m-d'),
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'André Trevisan',
                'descricao' => 'Aula de violino',
                'valor' => 120,
                "data_vencimento" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'every_20th',
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'CPFL',
                'descricao' => 'Força',
                'valor' => 150,
                "data_vencimento" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'fifth_business_day',
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'DAE',
                'descricao' => 'Água',
                'valor' => 50,
                "data_vencimento" => (new Carbon)->setDay(10)->format('Y-m-d'),
                'type' => 'fifth_business_day',
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'Rosângela Macário de Lima',
                'descricao' => 'Televisão',
                'valor' => 35 * 2,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Transferência via PIX: (19) 99181-7970 (Referente a televisão do Bruno, Paulo Afonso)',
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'Faculdade Einstein Limeira',
                'descricao' => 'Faculdade Mayara',
                'valor' => 648,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Pagamento via boleto',
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'Comgás',
                'descricao' => 'Gás do apartamento',
                'valor' => 5.00,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Pagamento via aplicativo',
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'Caixa Economica Federal',
                'descricao' => 'Apartamento',
                'valor' => 595,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Pagamento do NuBank para a Caixa',
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'Canção Nova',
                'descricao' => 'Doação pela Josefina Costa',
                'valor' => 50,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'observacao' => 'Pagamento via PIX',
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            // [
            //     'nome' => 'Josefina Costa',
            //     'descricao' => 'Faxina',
            //     'valor' => 150,
            //     'type' => 'fifth_business_day',
            //     'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
            // ],
            // [
            //     'nome' => 'Josefina Costa',
            //     'descricao' => 'Faculdade',
            //     'valor' => 200,
            //     'type' => 'fifth_business_day',
            //     'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
            // ],
            [
                'nome' => 'Vivo',
                'descricao' => 'Internet - Bruno Costa',
                'valor' => 139.99,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'Vivo',
                'descricao' => 'Internet - José Maria da Costa',
                'valor' => 119.99,
                'type' => 'fifth_business_day',
                'data_vencimento' => (new Carbon)->setDay(10)->format('Y-m-d'),
                'tipo' => Cobranca::$TIPO_DEBITO,
            ],
            [
                'nome' => 'Nubank',
                'descricao' => 'Cartão de crédito',
                'valor' => 1800,
                'type' => 'every_20th',
                'data_vencimento' => (new Carbon)->setDay('10')->format('Y-m-d'),
                'tipo' => Cobranca::$TIPO_DEBITO,
            ]
        ];
    }
}
