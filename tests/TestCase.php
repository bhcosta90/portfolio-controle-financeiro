<?php

namespace Tests;

use App\Models\Account;
use App\Models\Bank;
use App\Models\Tenant;
use Core\Application\BankAccount\Modules\Account\Repository\AccountRepository;
use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\BankAccount\Modules\Bank\Repository\BankRepository;
use Core\Application\Charge\Modules\Payment\Repository\PaymentRepository;
use Core\Application\Charge\Modules\Receive\Repository\ReceiveRepository;
use Core\Application\Relationship\Modules\Company\Repository\CompanyRepository;
use Core\Application\Relationship\Modules\Customer\Repository\CustomerRepository;
use Core\Application\Tenant\Repository\TenantRepository;
use Core\Application\Transaction\Repository\TransactionRepository;
use Core\Shared\Interfaces\EventManagerInterface;
use Core\Shared\Interfaces\TransactionInterface;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function debug($table, $id = null, $column = 'id')
    {
        $result = DB::table($table);
        if ($id) {
            $result->where($column, $id);
        }
        dump($result->get()->toArray());
    }

    /** @return TransactionInterface */
    protected function mockTransaction()
    {
        return app(TransactionInterface::class);
    }

    /** @return BankRepository */
    public function mockBankRepository()
    {
        return app(BankRepository::class);
    }

    /** @return TransactionRepository */
    public function mockTransactionRepository()
    {
        return app(TransactionRepository::class);
    }

    /** @return AccountRepository */
    public function mockAccountRepository()
    {
        return app(AccountRepository::class);
    }

    /** @return EventManagerInterface */
    public function mockEventManagerInterface()
    {
        return app(EventManagerInterface::class);
    }

    /** @return TenantRepository */
    public function mockTenantRepository()
    {
        return app(TenantRepository::class);
    }

    /** @return PaymentRepository */
    public function mockPaymentRepository()
    {
        return app(PaymentRepository::class);
    }

    /** @return ReceiveRepository */
    public function mockReceiveRepository()
    {
        return app(ReceiveRepository::class);
    }

    /** @return CompanyRepository */
    public function mockCompanyRepository()
    {
        return app(CompanyRepository::class);
    }

    /** @return CustomerRepository */
    public function mockCustomerRepository()
    {
        return app(CustomerRepository::class);
    }

    public function tenant()
    {
        return (string) Tenant::factory()->create([])->id;
    }

    public function bank($total = 1, $params = [])
    {
        $value = $params['value'] ?? null;
        unset($params['value']);

        $bank = Bank::factory($total)->create($params)->each(function ($obj) use ($value) {
            $param = [
                'tenant_id' => $obj->tenant_id,
                'entity_type' => BankEntity::class,
                'entity_id' => $obj->id,
            ];
            if ($value) {
                $param += [
                    'value' => $value
                ];
            }
            Account::factory()->create($param);
        });

        return $total > 1 ? $bank : $bank->first();
    }

    public function id()
    {
        return str()->uuid();
    }
}
