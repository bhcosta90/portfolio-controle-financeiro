<?php

namespace Core\Application\BankAccount\Modules\Bank\Domain;

use Core\Application\BankAccount\Modules\Account\Domain\AccountEntity;
use Core\Application\BankAccount\Modules\Bank\Events\{AddValueEvent, RemoveValueEvent};
use Core\Application\BankAccount\Shared\ValueObjects\AccountObject;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\{Input\NameInputObject, UuidObject};
use Core\Application\BankAccount\Shared\ValueObjects\BankObject;
use DateTime;

class BankEntity extends EntityAbstract
{
    protected AccountEntity $accountEntity;

    protected array $events = [];

    private function __construct(
        protected UuidObject $tenant,
        protected NameInputObject $name,
        protected float $value,
        protected bool $active,
        protected ?BankObject $bank = null,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $tenant,
        string $name,
        float $value,
        bool $active,
        ?string $bankCode = null,
        ?string $agency = null,
        ?string $agencyDigit = null,
        ?string $account = null,
        ?string $accountDigit = null,
        ?string $accountEntity = null,
        ?string $id = null,
        ?string $createdAt = null,
    ): self {
        $objBank = new self(
            new UuidObject($tenant),
            new NameInputObject($name, false, 'name'),
            $value,
            $active,
            null,
            $id ? new UuidObject($id) : null,
            $createdAt ? new DateTime($createdAt) : null,
        );
        $objBank->updateBankAccount($bankCode, $agency, $agencyDigit, $account, $accountDigit);
        $objBank->accountEntity = AccountEntity::create($tenant, $objBank->id(), $objBank, $value, $accountEntity);
        return $objBank;
    }

    public function update(
        string $name,
        ?string $bankCode = null,
        ?string $agency = null,
        ?string $agencyDigit = null,
        ?string $account = null,
        ?string $accountDigit = null,
    ) {
        $this->name = new NameInputObject($name, false, 'name');
        $this->updateBankAccount($bankCode, $agency, $agencyDigit, $account, $accountDigit);
    }

    public function updateValue($value)
    {
        $this->value = $value;
    }

    public function enable()
    {
        $this->active = true;
    }

    public function disable()
    {
        $this->active = false;
    }

    private function updateBankAccount(
        ?string $bankCode = null,
        ?string $agency = null,
        ?string $agencyDigit = null,
        ?string $account = null,
        ?string $accountDigit = null,
    ) {
        if ($bankCode && $agency && $account) {
            if (!empty($this->bank)) {
                throw new Exceptions\BankException('Bank details cannot be changed, please create a new bank account');
            }
            $this->bank = new BankObject(
                $bankCode,
                new AccountObject($agency, $agencyDigit),
                new AccountObject($account, $accountDigit),
            );
        }
    }
}
