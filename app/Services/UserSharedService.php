<?php

namespace App\Services;

use App\Models\User;
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

    public function update($data, $id)
    {
        $obj = $this->find($id);
        $obj->update($data);

        return $obj;
    }

    public function approved(User $user, $id)
    {
        $obj = $this->update([
            'user_shared_id' => $user->id,
        ], $id);


        $this->repository->firstOrCreate([
            'user_origin_id' => $user->id,
            'email' => $obj->userOrigin->email,
        ], [
            'status' => UserShared::$STATUS_ACCEPT,
            'user_origin_id' => $user->id,
            'user_shared_id' => $obj->user_origin_id,
        ]);

        return $obj;
    }

    public function myPendentsShared(string $email)
    {
        return $this->repository->where('email', $email)->where('status', UserShared::$STATUS_PENDING)->get();
    }
}
