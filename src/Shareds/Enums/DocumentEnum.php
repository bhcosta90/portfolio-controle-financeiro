<?php

namespace Costa\Shareds\Enums;

enum DocumentEnum: int {
    case CPF = 1;
    case CNPJ = 2;
    case PASSPORT = 3;
}