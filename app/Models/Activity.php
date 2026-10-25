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

    public function getIcon():string
    {
       return match ($this->action) {
           'register' => 'fa-user-plus',
           'login' => 'fa-sign-in-alt',
           'view' => 'fa-eye',
           default => 'fa-bell',
        };
    }


    public function actor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
