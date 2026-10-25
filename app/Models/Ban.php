<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $fillable = [
        'user_id',
        'banned_by',
        'reason',
        'expires_at',
        'lifted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'lifted_at' => 'datetime',
    ];

    /**
     * Забаненный пользователь.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Админ, который выдал бан.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    /**
     * Только активные баны: не снятые и не истёкшие.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereNull('lifted_at')
            ->where(function (Builder $query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Бан выдан навсегда?
     */
    public function getIsPermanentAttribute(): bool
    {
        return is_null($this->expires_at);
    }

    /**
     * Бан всё ещё действует?
     */
    public function getIsActiveAttribute(): bool
    {
        return is_null($this->lifted_at)
            && (is_null($this->expires_at) || $this->expires_at->isFuture());
    }
}
