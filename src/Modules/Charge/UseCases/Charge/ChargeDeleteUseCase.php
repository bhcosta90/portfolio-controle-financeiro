<?php

namespace Costa\Modules\Charge\UseCases\Charge;

use Costa\Shareds\ValueObjects\DeleteObject;
use Throwable;

class ChargeDeleteUseCase
{
    public function exec(DTO\Find\Input $input): DeleteObject
    {
        try {
            $ret = new DeleteObject(success: $this->repo->delete($input->id));
            $this->transaction->commit();
            return $ret;
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }
}
