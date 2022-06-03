<?php

namespace Costa\Modules\Charge\Utils\ValueObject;

class ParcelObject
{
    public function __construct(
        public $total,
        public $actual,
    ) {
        if ($this->actual > $this->total) {
            throw new Exceptions\ParcelObjectException('O total de parcelas é inferior a parcela atual');
        }
    }
}
