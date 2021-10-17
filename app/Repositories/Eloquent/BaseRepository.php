<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interface\BaseRepositoryInterface;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Spatie\Searchable\Search;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The repository model.
     *
     * @var Model
     */
    protected Model $model;

    /**
     * The query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected \Illuminate\Database\Eloquent\Builder $query;

    /**
     * Alias for the query limit.
     *
     * @var int
     */
    protected int $take;

    /**
     * Alias for only trash.
     *
     * @var boolean
     */
    protected bool $onlyTrashedModels;

    /**
     * Alias for with trashed models.
     *
     * @var boolean
     */
    protected bool $withTrashedModels;

    /**
     * Array of related models to eager load.
     *
     * @var array
     */
    protected array $with = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected array $appends = [];

    /**
     * Array of one or more where clause parameters.
     *
     * @var array
     */
    protected array $wheres = [];

    /**
     * Array of one or more where clause parameters.
     *
     * @var array
     */
    protected array $whereDates = [];

    /**
     * Array of one or more where in clause parameters.
     *
     * @var array
     */
    protected array $whereIns = [];

    /**
     * Array of one or more where not in clause parameters.
     *
     * @var array
     */
    protected array $whereNotIns = [];

    /**
     * Array of one or more ORDER BY column/value pairs.
     *
     * @var array
     */
    protected array $orderBys = [];

    /**
     * Array of scope methods to call on the model.
     *
     * @var array
     */
    protected array $scopes = [];

    /**
     * BaseRepository constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Specify Model class name.
     */
    abstract public function model(): string;

    /**
     * @return array
     */
    protected function searchFields(): array
    {
        return Schema::getColumnListing($this->model->getTable());
    }

    /**
     * @return Model|null
     * @throws Exception
     */
    public function makeModel(): Model|null
    {
        $model = app()->make($this->model());

        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of " . Model::class);
        }

        return $this->model = $model;
    }

    /**
     * Get all the model records in the database.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function all(array $columns = ['*']): Collection|array
    {
        $this->orderBy('created_at');
        $this->newQuery()->setClauses()->appendFields()->eagerLoad();

        $models = $this->query->get($columns);

        $this->unsetClauses();

        return $models;
    }

    /**
     * Count the number of specified model records in the database.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->get()->count();
    }

    /**
     * Create a new model record in the database.
     *
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        $this->unsetClauses();

        return $this->model->create($data);
    }

    /**
     * Create one or more new model records in the database.
     *
     * @param array $data
     *
     * @return Collection
     */
    public function createMultiple(array $data): Collection
    {
        $models = new Collection();

        foreach ($data as $d) {
            $models->push($this->create($d));
        }

        return $models;
    }


    /**
     * @param Model|int|array|null $id
     *
     * @return mixed
     */
    public function delete(Model|int|array $id = null): mixed
    {
        if (is_numeric($id)) {
            $this->unsetClauses();
            return $this->getById($id)->delete();
        }

        if (is_array($id)) {
            return $this->model->destroy($id);
        }

        if ($id instanceof Model) {
            return $id->delete();
        }

        $this->newQuery()->setClauses()->setScopes();

        $result = $this->query->delete();

        $this->unsetClauses();

        return $result;
    }


    /**
     * Get the first specified model record from the database.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|Model|null
     */
    public function first(array $columns = ['*']): Model|\Illuminate\Database\Eloquent\Builder|null
    {
        $this->newQuery()->appendFields()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->first($columns);

        $this->unsetClauses();

        return $model;
    }

    /**
     * Get all the specified model records in the database.
     *
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|Collection
     */
    public function get(array $columns = ['*']): Collection|array
    {
        $this->orderBy('created_at');

        $this->newQuery()->appendFields()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->get($columns);

        $this->unsetClauses();

        return $models;
    }

    /**
     * Get the specified model record from the database.
     *
     * @param       $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|Collection|Model|null
     */
    public function getById($id, array $columns = ['*']): Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $this->unsetClauses();

        $this->newQuery()->appendFields()->eagerLoad();

        return $this->query->find($id, $columns);
    }

    /**
     * @param int|string     $id
     * @param array|string[] $columns
     *
     * @return Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function find($id, array $columns = ['*']): Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        if (is_string($id)) {
            return $this->where('slug', $id)->first($columns);
        }
        return $this->getById($id, $columns);
    }

    /**
     * @param       $item
     * @param       $column
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|Model|null
     */
    public function getByColumn($item, $column, array $columns = ['*']): Model|\Illuminate\Database\Eloquent\Builder|null
    {
        $this->unsetClauses();

        $this->newQuery()->appendFields()->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }

    /**
     * @param int    $limit
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @return LengthAwarePaginator|null
     */
    public function paginate(int $limit = 25, array $columns = ['*'], string $pageName = 'page', $page = null): ?LengthAwarePaginator
    {
        $this->newQuery()->appendFields()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    /**
     * Update the specified model record in the database.
     *
     * @param integer|Model $id
     * @param array         $data
     * @param array         $options
     *
     * @return Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null
     */
    public function update(Model|int $id, array $data, array $options = []): Model|Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        $this->unsetClauses();

        $model = null;
        if (is_numeric($id)) {
            $model = $this->getById($id);
        } elseif ($id instanceof Model) {
            $model = $id;
        }

        $model->update($data, $options);

        return $model;
    }

    /**
     * Set the query limit.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'desc'): static
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * Add a simple where clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return $this
     */
    public function where(string $column, string $value, string $operator = '='): static
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed  $values
     *
     * @return $this
     */
    public function whereIn(string $column, mixed $values): static
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed  $values
     *
     * @return $this
     */
    public function whereNotIn(string $column, mixed $values): static
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereNotIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Add a simple where date clause to the query.
     *
     * @param string $column
     * @param        $value
     * @param string $operator
     *
     * @return $this
     */
    public function whereDate(string $column, $value, string $operator = '='): static
    {
        $this->whereDates[] = compact('column', 'value', 'operator');

        return $this;
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations): static
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    /**
     * Set Eloquent columns to append.
     *
     * @param array $appends
     *
     * @return $this
     */
    public function append(array $appends): static
    {
        if (is_string($appends)) {
            $appends = func_get_args();
        }

        $this->appends = $appends;

        return $this;
    }

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery(): static
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad(): static
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }
        return $this;
    }

    /**
     * Append accessors to the model's array form.
     *
     * @return $this
     */
    protected function appendFields(): static
    {
        foreach ($this->appends as $append) {
            $this->query->append($append);
        }
        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses(): static
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereDates as $where) {
            $this->query->whereDate($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->whereNotIns as $whereNotIn) {
            $this->query->whereNotIn($whereNotIn['column'], $whereNotIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (isset($this->take) && !is_null($this->take) && ($this->take != 0)) {
            $this->query->take($this->take);
        }

        if (isset($this->withTrashedModels)) {
            //            $this->query->withTrashed();
        }

        if (isset($this->onlyTrashedModels)) {
            //            $this->query->onlyTrashed();
        }

        return $this;
    }

    /**
     * Set query scopes.
     *
     * @return $this
     */
    protected function setScopes(): static
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    /**
     * Get with trashed
     *
     * @return $this
     */
    public function getWithTrashed(): static
    {
        $this->withTrashedModels = true;

        return $this;
    }

    /**
     * Get only trashed
     *
     * @return $this
     */
    public function getOnlyTrashed(): static
    {
        $this->onlyTrashedModels = true;

        return $this;
    }

    /**
     * Restore trashed model
     *
     * @param int|null $id
     *
     * @return boolean
     */
    public function restore(int $id = null): bool
    {
        if ($id) {
            return $this->getById($id)->restore();
        }
        $this->newQuery()->appendFields()->eagerLoad()->setClauses()->setScopes();
        return $this->query->restore();
    }

    /**
     * Permanently delete model
     *
     * @param mixed|null $id
     *
     * @return boolean
     */
    public function permanentlyDelete($id = null): bool
    {
        if ($id) {
            if (is_numeric($id)) {
                return $this->getById($id)->forceDelete();
            } elseif ($id instanceof Model) {
                return $id->forceDelete();
            }
        }

        $this->newQuery()->appendFields()->eagerLoad()->setClauses()->setScopes();
        return $this->query->forceDelete();
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */
    protected function unsetClauses(): static
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->whereNotIns = [];
        $this->scopes = [];
        $this->take = 0;
        $this->withTrashedModels = false;
        $this->withTrashedModels = false;

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function tags(): \Illuminate\Support\Collection
    {
        $tags = [];
        $retVal = $this->all();
        $retVal->each(function ($item) {
            foreach ($item->tags as $tag) {
                $tags[] = $tag->id;
            }
        });
        $helper = new Helper;
        $tags = $helper->removeDuplicatesInArray($tags);

        $result = collect();
        foreach ($tags as $id) {
            $result->push(Tag::find($id));
        }
        return $result;
    }

    /**
     * @param string $searchString
     *
     * @return array
     */
    public function search(string $searchString): array
    {
        $result = [];

        $searchResults = (new Search())
            ->registerModel($this->model(), $this->searchFields())
            ->search($searchString);
        if ($searchResults->count() > 0) {
            foreach ($searchResults->groupByType() as $modelSearchResults) {
                foreach ($modelSearchResults as $searchResult) {
                    $result[] = $this->find($searchResult->title);
                }
            }
        }

        return $result;
    }
}
