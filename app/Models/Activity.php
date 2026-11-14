<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable =[
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id'
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function getIcon(): string
    {
       return match ($this->action) {
           'register' => 'bi-person-plus',
           'login' => 'bi-box-arrow-in-right',
           'logout' => 'bi-box-arrow-right',
           'view' => 'bi-eye',
           'created' => 'bi-plus-circle',
           'updated' => 'bi-pencil',
           'deleted' => 'bi-trash3',
           'banned' => 'bi-slash-circle',
           'unbanned' => 'bi-check-circle',
           'message_sent' => 'bi-envelope',
           'comment' => 'bi-chat-dots',
           'like' => 'bi-hand-thumbs-up',
           'favorite' => 'bi-star',
           'rate' => 'bi-star-half',
           'watchLater' => 'bi-collection-play',
           default => 'bi-activity',
        };
    }


    public function actor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
