<?php

namespace App\Services;

use App\Models\UserShared;

class UserSharedService
{
    private UserShared $repository;

    public function __construct(UserShared $repository)
    {
        /** @var Eloquent */
        $this->repository = $repository;
    }

    public function data()
    {
        return $this->repository;
    }

    public function find($id)
    {
        return $this->repository->where('uuid', $id)->firstOrFail();
    }

    public function delete($id)
    {
        return $this->repository->where('id', $id)->delete();
    }

    public function shared(int $idUser, string $email)
    {
        return $this->repository->create([
            'user_origin_id' => $idUser,
            'email' => $email,
            'status' => UserShared::$STATUS_PENDING,
        ]);
    }

    public function myPendentsShared(string $email)
    {
        return $this->repository->where('email', $email)->where('status', UserShared::$STATUS_PENDING)->get();
    }
}
