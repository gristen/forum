<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    public function scopeSearch(Builder $query, $search, $columns)
    {
       return  $query->where(function ($query) use ($search, $columns) {
           foreach ($columns as $column) {
               $query->orWhere($column, 'LIKE', "%{$search}%");
           }
       });
    }
}
