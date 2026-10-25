<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasSearch;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasSearch;

    /** Колонки, по которым ищет scopeSearch(). */
    protected array $searchable = ['name', 'email'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'about',
        ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function conversations()
    {
       return $this->belongsToMany(Conversation::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class);
    }

    public function bans()
    {
        return $this->hasMany(Ban::class);
    }

    /**
     * Действующий бан пользователя (если есть).
     */
    public function currentBan(): ?Ban
    {
        return $this->bans()->active()->latest()->first();
    }

    /**
     * Забанен ли пользователь прямо сейчас.
     */
    public function isBanned(): bool
    {
        return $this->bans()->active()->exists();
    }

    /**
     * Фильтр по названию роли.
     */
    public function scopeFilterByRole(Builder $query, ?string $role): Builder
    {
        return $query->when(
            filled($role),
            fn (Builder $query) => $query->whereHas(
                'role',
                fn (Builder $roleQuery) => $roleQuery->where('name', $role)
            )
        );
    }

    /**
     * Сортировка списка пользователей.
     */
    public function scopeSorted(Builder $query, ?string $sort = 'latest'): Builder
    {
        return match ($sort) {
            'oldest' => $query->oldest(),
            'name' => $query->orderBy('name'),
            default => $query->latest(),
        };
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
