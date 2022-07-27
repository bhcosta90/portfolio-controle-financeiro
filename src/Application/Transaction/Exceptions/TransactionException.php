<?php

namespace Core\Application\Transaction\Exceptions;

use Exception;

class TransactionException extends Exception
{
    public $code = 402;
}
