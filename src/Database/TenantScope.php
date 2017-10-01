<?php

namespace Silber\Bouncer\Database;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class TenantScope implements ScopeInterface
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $query, Model $model)
    {
        Models::scope()->applyToModelQuery($query, $model->getTable());
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function remove(Builder $query, Model $model)
    {
        $query = $query->getQuery();

        $query->wheres = (new Collection($query->wheres))->reject(function ($where) {
            return $this->isScopeConstraint($where);
        })->values()->all();
    }

    /**
     * Determine whether the given "where" is a scope constraint.
     *
     * @param  array  $where
     * @return bool
     */
    protected function isScopeConstraint($where)
    {
        return $where['type'] == 'Null' && $where['column'] == 'scope';
    }
}
