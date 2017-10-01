<?php

namespace Silber\Bouncer\Database;

use Illuminate\Database\Eloquent\Model;

class Scope
{
    /**
     * The tenant ID by which to scope all queries.
     *
     * @var mixed
     */
    protected $scope = null;

    /**
     * Determines whether the scope is only applied to relationships.
     *
     * Set this to true to also apply the scopes to the role/ability models.
     *
     * @var mixed
     */
    protected $onlyScopeRelationships = false;

    /**
     * Scope all queries to the given tenant ID.
     *
     * @param  mixed  $id
     * @return void
     */
    public function scopeTo($id)
    {
        $this->scope = $id;

        $this->onlyScopeRelationships = false;
    }

    /**
     * Scope only the relationships to the given tenant ID.
     *
     * @param  mixed  $id
     * @return void
     */
    public function scopeRelationshipsTo($id)
    {
        $this->scope = $id;

        $this->onlyScopeRelationships = true;
    }

    /**
     * Scope the given model to the current tenant.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function applyToModel(Model $model)
    {
        if (! $this->onlyScopeRelationships && ! is_null($this->scope)) {
            $model->scope = $this->scope;
        }

        return $model;
    }

    /**
     * Scope the given model query to the current tenant.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function applyToModelQuery($query, $table)
    {
        if (! is_null($this->scope) && ! $this->onlyScopeRelationships) {
            $query->where("{$table}.scope", $this->scope);
        }

        return $query;
    }

    /**
     * Scope the given relationship query to the current tenant.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function applyToRelationship($query, $table)
    {
        if (! is_null($this->scope)) {
            $query->where("{$table}.scope", $this->scope);
        }

        return $query;
    }
}
