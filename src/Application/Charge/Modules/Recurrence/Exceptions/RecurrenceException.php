<?php

namespace Core\Application\Charge\Modules\Recurrence\Exceptions;

use Exception;

class RecurrenceException extends Exception
{
    public $code = 400;
}
