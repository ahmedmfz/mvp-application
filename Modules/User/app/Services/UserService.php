<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\Package\Models\Package;
use Modules\User\Events\UserCreated;
use Modules\User\Events\UserUpdated;
use Modules\User\Events\UserDeleted;
use Modules\User\Models\User;
use Modules\User\Transformers\UserResource;


class UserService {

    public function getAll(int $perPage = 15)
    {
        $page = request()->query('page', 1);
        $cacheKey = "users_consumers_page_{$page}_limit_{$perPage}";

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($perPage) {
            return User::with('activePackage')
                       ->where('type', 'consumer')
                       ->paginate($perPage);
        });
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            DB::afterCommit(function () use ($user) {
                Cache::flush();
                event(new UserCreated($user->id));
            });

            return $user;
        });
    }

    public function update($data, $user)
    {
        return DB::transaction(function () use ($data, $user) {
            if(isset($data['password'])){
                $data['password'] = bcrypt($data['password']);
            }
        
            $user->update($data);

            DB::afterCommit(function () use ($user) {
                Cache::flush();
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
                Cache::flush();
                event(new UserDeleted($userId));
            });
            return $user;
        });
    }   

}