<?php

namespace App\Models;

use App\Traits\HasSearch;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasSearch;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'parent_id',
        'order',
    ];

    public function getConfigAttribute()
    {
        return config("search.$this->name");
    }
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

}
