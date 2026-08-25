<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'parent_id',
        'order',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
