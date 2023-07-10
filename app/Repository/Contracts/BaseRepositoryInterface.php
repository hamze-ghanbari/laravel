<?php

namespace App\Repository\Contracts;

use Closure;

interface BaseRepositoryInterface
{
    public function all($columns = array('*'));

    public function first($columns = array('*'));

    public function paginate($limit = null, $columns = ['*']);

    public function find($id, $columns = ['*']);

    public function findByField($field, $value, $operator = null, $columns = ['*']);

    public function findWhere(array $where, $columns = ['*']);

    public function findWhereIn($field, array $values, $columns = ['*']);

    public function findWhereNotIn($field, array $values, $columns = ['*']);

    public function findWhereBetween($field, array $values, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function delete($id);

    public function deleteWhere(array $where);

    public function orderBy($column, $direction = 'asc');

    public function with(array $relations);

    public function has(string $relation);

    public function whereHas(string $relation, closure $closure);


}
