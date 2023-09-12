<?php

namespace App\Models\Enum\Charge;

enum TypeEnum: int
{
    case UNIQUE = 1;
    case PARCEL = 2;
    case MONTHLY = 3;

}
