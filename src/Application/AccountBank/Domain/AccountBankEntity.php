<?php

namespace Core\Application\AccountBank\Domain;

use Core\Application\AccountBank\Events\{AddValueEvent, RemoveValueEvent};
use Core\Application\AccountBank\ValueObjects\AccountObject;
use Core\Application\AccountBank\ValueObjects\BankObject;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\Contracts\ValueInterface;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\Input\NameInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;
use Exception;

class AccountBankEntity extends EntityAbstract implements ValueInterface
{
    protected array $events = [];

    private function __construct(
        protected NameInputObject $name,
        protected float           $value,
        protected ?UuidObject     $tenant,
        protected ?BankObject     $bank = null,
        protected ?UuidObject     $id = null,
        protected ?DateTime       $createdAt = null,
    )
    {
        parent::__construct();
    }

    public static function create(
        string  $tenant,
        string  $name,
        float   $value,
        ?string $bankCode = null,
        ?string $agency = null,
        ?string $agencyDigit = null,
        ?string $account = null,
        ?string $accountDigit = null,
        ?string $id = null,
        ?string $createdAt = null,
    ): self
    {
        if ($bankCode && $agency && $account) {
            $bank = new BankObject(
                $bankCode,
                new AccountObject($agency, $agencyDigit),
                new AccountObject($account, $accountDigit),
            );
        }
        return new self(
            new NameInputObject($name, false, 'name'),
            $value,
            new UuidObject($tenant),
            $bank ?? null,
            $id ? new UuidObject($id) : null,
            $createdAt ? new DateTime($createdAt) : null,
        );
    }

    public function update(
        string $name,
        float $value,
        ?string $bankCode = null,
        ?string $agency = null,
        ?string $agencyDigit = null,
        ?string $account = null,
        ?string $accountDigit = null,
    )
    {
        if ($bankCode && $agency && $account) {
            if (!empty($this->bank)) {
                throw new Exception('Bank details cannot be changed, please create a new bank account');
            }
            $bank = new BankObject(
                $bankCode,
                new AccountObject($agency, $agencyDigit),
                new AccountObject($account, $accountDigit),
            );
            $this->bank = $bank;
        }

        $this->name = new NameInputObject($name, false, 'name');
        $this->value = $value;
    }

    public function addValue(float $value, string $idPayment)
    {
        $this->value += $value;
        $this->events[] = new AddValueEvent($this, new FloatInputObject($value), new UuidObject($idPayment));
    }

    public function removeValue(float $value, string $idPayment)
    {
        $this->value -= $value;
        $this->events[] = new RemoveValueEvent($this, new FloatInputObject($value), new UuidObject($idPayment));
    }
}
