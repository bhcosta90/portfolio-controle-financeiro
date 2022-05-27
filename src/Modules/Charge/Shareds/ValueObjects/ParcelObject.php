<?php

namespace Costa\Modules\Charge\Shareds\ValueObjects;

use Exception;

class ParcelObject
{
    public function __construct(
        public int $total,
        public int $parcel,
    ) {
        if ($this->parcel > $this->total) {
            throw new Exception('The installment number cannot be greater than the total');
        }
    }
}
