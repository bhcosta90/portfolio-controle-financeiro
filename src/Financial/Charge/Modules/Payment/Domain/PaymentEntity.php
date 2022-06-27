<?php

namespace Core\Financial\Charge\Modules\Payment\Domain;

use Core\Financial\Charge\Shared\Enums\ChargeStatusEnum;
use Core\Financial\Charge\Shared\Enums\ChargeTypeEnum;
use Core\Financial\Relationship\Modules\Company\Domain\CompanyEntity;
use Core\Shared\Abstracts\EntityAbstract;
use Core\Shared\ValueObjects\UuidObject;
use DateTime;
use DomainException;

class PaymentEntity extends EntityAbstract
{
    protected ChargeStatusEnum $status;

    private function __construct(
        protected UuidObject $group,
        protected float $value,
        protected CompanyEntity $company,
        protected ChargeTypeEnum $type,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public static function create(
        string $group,
        float $value,
        CompanyEntity $company,
        int $type,
        ?string $id = null,
        ?string $createdAt = null,
    ) {
        $obj = new self(new UuidObject($group), $value, $company, ChargeTypeEnum::from($type), $id, $createdAt);
        $obj->status = ChargeStatusEnum::PENDING;
        $obj->validate();
        return $obj;
    }

    public function update(
        float $value,
        CompanyEntity $company,
    ) {
        $this->value = $value;
        $this->company = $company;
        $this->validate();
    }

    protected function validate()
    {
        if ($this->value <= 0) {
            throw new DomainException('Payment amount cannot be free');
        }
    }
}
