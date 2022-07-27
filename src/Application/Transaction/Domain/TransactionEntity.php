<?php

namespace Core\Application\Transaction\Domain;

use Core\Application\BankAccount\Modules\Bank\Domain\BankEntity;
use Core\Application\Transaction\Events\ExecutePaymentEvent;
use Core\Application\Transaction\Shared\Enums\TransactionStatusEnum;
use Core\Application\Transaction\Shared\Enums\TransactionTypeEnum;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\EntityObject;
use Core\Shared\ValueObjects\Input\FloatInputObject;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;

class TransactionEntity extends EntityAbstract
{
    protected array $events = [];
    protected float $previousValue = 0;

    private function __construct(
        protected UuidObject $tenant,
        protected UuidObject $group,
        protected string $title,
        protected ?UuidObject $accountTo,
        protected UuidObject $accountFrom,
        protected ?EntityObject $entity,
        protected ?EntityObject $relationship,
        protected FloatInputObject $value,
        protected TransactionTypeEnum $type,
        protected TransactionStatusEnum $status,
        protected DateTime $dateExecute,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $tenant,
        string $group,
        string $title,
        string $accountTo,
        string $accountFrom,
        string $transaction_id,
        string|object $transaction_type,
        ?string $relationship_id,
        string|object|null $relationship_type,
        ?string $relationship_name,
        float $value,
        int $type,
        ?string $date_execute,
        int $status = null,
        ?string $id = null,
        ?string $createdAt = null,
    ) {
        $obj = new self(
            new UuidObject($tenant),
            new UuidObject($group),
            $title,
            new UuidObject($accountTo),
            $accountFrom ? new UuidObject($accountFrom) : null,
            new EntityObject($transaction_id, $transaction_type),
            $relationship_id && $relationship_type && $relationship_name
                ? new EntityObject($relationship_id, $relationship_type, $relationship_name)
                : null,
            new FloatInputObject($value),
            TransactionTypeEnum::from($type),
            $status ? TransactionStatusEnum::from($status) : TransactionStatusEnum::PENDING,
            $date_execute ? new DateTime($date_execute) : (new DateTime())->modify('+2 minutes'),
            $id ? new UuidObject($id) : null,
            $createdAt ? new DateTime($createdAt) : null,
        );

        $today = (new DateTime())->format('Y-m-d');
        $date = $obj->dateExecute->format('Y-m-d');
        if ($today >= $date) {
            $obj->events[] = new ExecutePaymentEvent($tenant, $obj->id());
        }
        return $obj;
    }

    public function completed()
    {
        $this->status = TransactionStatusEnum::COMPLETE;
    }

    public function setValuePrevious($value)
    {
        $this->previousValue = $value;
    }
}
