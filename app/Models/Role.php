<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    public function getConfigAttribute()
    {
        return config("roles.$this->name");
    }
    public function getIconClassAttribute()
    {
        return $this->config['icon'];
    }

    public function getBadgeClassAttribute()
    {
        return $this->config['color'];
    }
    public function getDisplayNameAttribute()
    {
        return $this->config['name'];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class,'role_permissions');
    }
}
