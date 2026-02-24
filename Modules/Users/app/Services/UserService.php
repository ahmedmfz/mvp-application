<?php

namespace Modules\Users\Services;

use Illuminate\Support\Facades\DB;
use Modules\Users\Events\UserCreated;
use Modules\Users\Models\User;


class UserService {

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create($data);

            DB::afterCommit(function () use ($user) {
                event(new UserCreated($user));
            });

            return $user;
        });
    }


    public function update($data, $user)
    {
        return DB::transaction(function () use ($data, $user) {
            $user->update($data);
            return $user;
        });
    }

    public function destroy($user)
    {
        return DB::transaction(function () use ($user) {
            $user->delete();
            return $user;
        });
    }   

}