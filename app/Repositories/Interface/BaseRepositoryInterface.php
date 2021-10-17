<?php

namespace App\Repositories\Interface;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repositories
 */
interface BaseRepositoryInterface
{
    /**
     * @return Model|null
     * @throws Exception
     */
    public function makeModel(): Model|null;

    /**
     * Get all the model records in the database.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function all(array $columns = ['*']): Collection|array;

    /**
     * Count the number of specified model records in the database.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Create a new model record in the database.
     *
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Create one or more new model records in the database.
     *
     * @param array $data
     *
     * @return Collection
     */
    public function createMultiple(array $data): Collection;

    /**
     * Delete one or more model records from the database.
     *
     * @param integer|array|Model|null $id
     *
     * @return mixed
     */
    public function delete(Model|int|array $id = null): mixed;

    /**
     * Get the first specified model record from the database.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|Model|null
     */
    public function first(array $columns = ['*']): Model|\Illuminate\Database\Eloquent\Builder|null;

    /**
     * Get all the specified model records in the database.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function get(array $columns = ['*']): Collection|array;

    /**
     * Get the specified model record from the database.
     *
     * @param       $id
     * @param array $columns
     *
     * @return Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function getById($id, array $columns = ['*']): Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null;

    /**
     * @param $id
     * @param array|string[] $columns
     * @return Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function find($id, array $columns = ['*']): Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null;

    /**
     * @param       $item
     * @param       $column
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|Model|null
     */
    public function getByColumn($item, $column, array $columns = ['*']): \Illuminate\Database\Eloquent\Builder|Model|null;

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return LengthAwarePaginator|null
     */
    public function paginate(int $limit = 25, array $columns = ['*'], string $pageName = 'page', $page = null): ?LengthAwarePaginator;

    /**
     * Update the specified model record in the database.
     *
     * @param integer|Model $id
     * @param array $data
     * @param array $options
     *
     * @return Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function update(Model|int $id, array $data, array $options = []): Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null;

    /**
     * Set the query limit.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit): static;

    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): static;

    /**
     * Add a simple where clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return $this
     */
    public function where(string $column, string $value, string $operator = '='): static;

    /**
     * Add a simple where date clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return $this
     */
    public function whereDate(string $column, string $value, string $operator = '='): static;

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return $this
     */
    public function whereIn(string $column, mixed $values): static;

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations): static;

    /**
     * Set Eloquent columns to append.
     *
     * @param array $appends
     *
     * @return $this
     */
    public function append(array $appends): static;

    /**
     * Get with trashed
     *
     * @return $this
     */
    public function getWithTrashed(): static;

    /**
     * Get only trashed
     *
     * @return $this
     */
    public function getOnlyTrashed(): static;

    /**
     * Restore trashed model
     *
     * @param integer|null $id
     *
     * @return boolean
     */
    public function restore(int $id = null): bool;

    /**
     * Permanently delete model
     *
     * @param integer|null $id
     *
     * @return boolean
     */
    public function permanentlyDelete(int $id = null): bool;

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */

    /**
     * @return \Illuminate\Support\Collection
     */
    public function tags(): \Illuminate\Support\Collection;

    /**
     * @param string $searchString
     * @return array
     */
    public function search(string $searchString): array;
}
