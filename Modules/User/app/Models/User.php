<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    /**
     * Determine whether the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Determine whether the user is a consumer.
     */
    public function isConsumer(): bool
    {
        return $this->type === 'consumer';
    }

    public function getToken(string $type)
    {
        $this->tokens()->delete();
        return $this->createToken(uniqid(), [$type])->plainTextToken;
    }
}
