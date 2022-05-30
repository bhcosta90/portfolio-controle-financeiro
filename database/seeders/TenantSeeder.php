<?php

namespace Database\Seeders;

use Costa\Modules\Relationship\Customer\UseCases\CreateUseCase as CustomerCreateUseCase;
use Costa\Modules\Relationship\Supplier\UseCases\CreateUseCase as SupplierCreateUseCase;
use Costa\Modules\Charge\Receive\UseCases\CreateUseCase as ReceiveCreateUseCase;
use Costa\Modules\Charge\Payment\UseCases\CreateUseCase as PaymentCreateUseCase;
use Costa\Modules\Recurrence\UseCases\CreateUseCase as RecurrenceCreateUseCase;
use Costa\Modules\Bank\UseCases\CreateUseCase as BankCreateUseCase;

use Costa\Modules\Relationship\Customer\UseCases\DTO\Create\Input as CustomerInput;
use Costa\Modules\Relationship\Supplier\UseCases\DTO\Create\Input as SupplierInput;
use Costa\Modules\Charge\Receive\UseCases\DTO\Create\Input as ReceiveInput;
use Costa\Modules\Charge\Payment\UseCases\DTO\Create\Input as PaymentInput;
use Costa\Modules\Recurrence\UseCases\DTO\Create\Input as RecurrenceInput;
use Costa\Modules\Bank\UseCases\DTO\Create\Input as BankInput;
use DateTime;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $total = 10;
        \App\Models\User::factory()->create([
            'name' => 'Suporte administrativo',
            'email' => 'suporte@noreply.com',
            'password' => '$2y$10$mZOsus8yaBMZNHH3tPQKMeEr4XACZwpqcN.VIJq2V1i01PRoYZAge',
        ]);

        if (tenant()->id == '0b26c3fd-bb33-4419-867a-5aee383353f5') {
            \App\Models\User::factory()->create([
                'name' => 'Bruno Henrique da Costa',
                'email' => 'bhcosta90@gmail.com',
                'password' => '$2y$10$A5txKXUODkQyLrTOdOA7g.fzu/xY5lbHtp/MkCdPX7wwKcJ9h1LFu',
            ]);
            $total--;
        }

        if (file_exists($file = storage_path('data.json'))) {
            $data = json_decode(file_get_contents($file), true);
            $dataCache = [];

            foreach (($data['receive'] ?? []) as $rs) {
                if (empty($dataCache['customer'][$rs['name']])) {
                    /** @var CustomerCreateUseCase */
                    $objCustomer = app(CustomerCreateUseCase::class);
                    $dataCache['customer'][$rs['name']] = $objCustomer->handle(new CustomerInput(
                        name: $rs['name']
                    ));
                }


                if (($rs['recurrence'] ?? null) && empty($dataCache['recurrence'][$rs['recurrence']])) {
                    $dataCache['recurrence'][$rs['recurrence']] = $this->registerRecurrence($rs['recurrence']);
                }

                /** @var ReceiveCreateUseCase */
                $objCharge = app(ReceiveCreateUseCase::class);
                $objCharge->handle([
                    new ReceiveInput(
                        title: $rs['title'],
                        description: $data['description'] ?? null,
                        customerId: $dataCache['customer'][$rs['name']]->id,
                        value: $rs['value'],
                        date: new DateTime($rs['date']),
                        parcel: $rs['parcel'] ?? 1,
                        recurrence: !empty($rs['recurrence']) ? $dataCache['recurrence'][$rs['recurrence']]->id : null,
                    )
                ]);
            }

            foreach (($data['payment'] ?? []) as $rs) {
                if (empty($dataCache['supplier'][$rs['name']])) {
                    /** @var SupplierCreateUseCase */
                    $objCustomer = app(SupplierCreateUseCase::class);
                    $dataCache['supplier'][$rs['name']] = $objCustomer->handle(new SupplierInput(
                        name: $rs['name']
                    ));
                }

                if (($rs['recurrence'] ?? null) && empty($dataCache['recurrence'][$rs['recurrence']])) {
                    $dataCache['recurrence'][$rs['recurrence']] = $this->registerRecurrence($rs['recurrence']);
                }

                /** @var PaymentCreateUseCase */
                $objCharge = app(PaymentCreateUseCase::class);
                $objCharge->handle([
                    new PaymentInput(
                        title: $rs['title'],
                        description: $data['description'] ?? null,
                        supplierId: $dataCache['supplier'][$rs['name']]->id,
                        value: $rs['value'],
                        date: new DateTime($rs['date']),
                        parcel: $rs['parcel'] ?? 1,
                        recurrence: !empty($rs['recurrence']) ? $dataCache['recurrence'][$rs['recurrence']]->id : null,
                    )
                ]);
            }

            foreach (($data['bank'] ?? []) as $rs) {
                /** @var BankCreateUseCase */
                $objBank = app(BankCreateUseCase::class);
                $objBank->handle(new BankInput(name: $rs['name'], value: $rs['value']));
            }
        }

        \App\Models\User::factory($total - 1)->create([]);
    }

    private function registerRecurrence($recurrence)
    {
        $days = match ($recurrence) {
            default => 30
        };

        /** @var RecurrenceCreateUseCase */
        $objRecurrence = app(RecurrenceCreateUseCase::class);
        
        return $objRecurrence->handle(
            new RecurrenceInput(
                name: 'Mensal',
                days: $days
            )
        );
    }
}
