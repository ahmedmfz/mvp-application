<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Package\Models\Package;
use Modules\Package\Models\UserSubscription;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function isConsumer(): bool
    {
        return $this->type === 'consumer';
    }

    public function getToken(string $type)
    {
        $this->tokens()->delete();
        return $this->createToken(uniqid(), [$type])->plainTextToken;
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Package::class , 'user_subscriptions' , 'user_id' , 'package_id');
    }

    public function activePackage()
    {
        return $this->belongsToMany(Package::class, 'user_subscriptions', 'user_id', 'package_id')
                     ->wherePivot('status', 'active')
                     ->withPivot(['status', 'started_at'])
                     ->latest('user_subscriptions.id');
    }
}
