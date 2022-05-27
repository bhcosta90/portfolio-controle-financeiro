<?php

namespace Costa\Modules\Account\Entities;

use Costa\Modules\Account\ValueObjects\BankObject;
use Costa\Shareds\Abstracts\EntityAbstract;
use Costa\Shareds\ValueObjects\Input\InputNameObject;
use Costa\Shareds\ValueObjects\UuidObject;
use DateTime;

class BankEntity extends EntityAbstract
{
    public function __construct(
        public InputNameObject $name,
        public ?BankObject $bank = null,
        public bool $active = true,
        protected ?UuidObject $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        parent::__construct();
    }

    public function update(
        InputNameObject $name,
        ?BankObject $bank = null,
    ): self {
        $this->name = $name;
        $this->bank = $bank;
        return $this;
    }
}
