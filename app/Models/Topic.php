<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Topic extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'user_id',
        'content',
        'category_id',
        'last_post_id',
        'last_post_user_id',
        'views',
        'locked',
        'pinned',
        'solved',
    ];

    public function getShortTitleAttribute()
    {
        return Str::limit($this->title, 50);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
