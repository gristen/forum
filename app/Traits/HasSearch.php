<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    /**
     * Поиск по указанным колонкам.
     *
     * Колонки можно передать явно, иначе берутся из свойства
     * $searchable модели:
     *
     *     protected array $searchable = ['name', 'email'];
     */
    public function scopeSearch(Builder $query, ?string $search, ?array $columns = null): Builder
    {
        $search = trim((string) $search);

        $columns ??= $this->searchable ?? [];

        if ($search === '' || $columns === []) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$search}%");
            }
        });
    }
}
