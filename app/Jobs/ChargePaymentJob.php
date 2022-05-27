<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Tenant;
use Costa\Modules\Account\Entities\AccountEntity;
use Costa\Modules\Account\Repository\AccountRepositoryInterface;
use Costa\Modules\Payment\Repository\PaymentRepositoryInterface;
use Costa\Modules\Payment\Shareds\Enums\Type;
use Costa\Modules\Payment\UseCases\PaymentFindAndPayUseCase;
use Costa\Modules\Payment\UseCases\DTO\FindAndPay\Input as FindAndPayInput;
use Costa\Shareds\ValueObjects\Input\InputValueObject;
use Costa\Shareds\ValueObjects\ModelObject;
use Costa\Shareds\ValueObjects\UuidObject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ChargePaymentJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private string $uuid,
        private int $type,
        private float $value,
        private int $accountId,
        private int $tenantId,
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PaymentFindAndPayUseCase $uc, Account $account, Tenant $tenant)
    {
        $objAccount = $account->find($this->accountId);
        $objTenant = $tenant->find($this->tenantId);

        $uc->exec(new FindAndPayInput(
            id: new UuidObject($this->uuid),
            type: Type::from($this->type),
            value: new InputValueObject($this->value),
            account: new ModelObject(
                id: $objAccount->model_id,
                type: $objAccount->model_type
            ),
            accounts: [new ModelObject(id: $objTenant->uuid, type: $objTenant)],
        ));
    }

    public function uniqueId()
    {
        if (app()->isLocal()) {
            return time();
        }
        
        return $this->uuid;
    }
}
