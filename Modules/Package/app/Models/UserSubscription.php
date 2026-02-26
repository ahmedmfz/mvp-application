<?php

namespace Modules\Package\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id',
        'package_id',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\Modules\User\Models\User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->whereNull('ended_at');
    }
}
