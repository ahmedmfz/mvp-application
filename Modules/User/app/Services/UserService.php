<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Modules\User\Events\UserCreated;
use Modules\User\Events\UserUpdated;
use Modules\User\Events\UserDeleted;
use Modules\User\Models\User;


class UserService {

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create($data);

            DB::afterCommit(function () use ($user) {
                event(new UserCreated($user->id));
            });

            return $user;
        });
    }

    public function update($data, $user)
    {
        return DB::transaction(function () use ($data, $user) {
            $user->update($data);

            DB::afterCommit(function () use ($user) {
                event(new UserUpdated($user->id));
            });

            return $user;
        });
    }

    public function destroy($user)
    {
        return DB::transaction(function () use ($user) {
            $userId = $user->id;
            $user->delete();

            DB::afterCommit(function () use ($userId) {
                event(new UserDeleted($userId));
            });
            return $user;
        });
    }   

}