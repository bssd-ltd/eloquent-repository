<?php

namespace Bssd\EloquentRepository\Repository\Concerns;

use Bssd\EloquentRepository\Repository\Contracts\Cacheable;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property-read Builder|Model $model
 * @property-read Factory       $cache
 * @method string cacheKey()
 * @method int cacheTTLValue()
 * @mixin \Bssd\EloquentRepository\EloquentRepository
 */
trait SelectsEntity
{
    /**
     * Returns all models.
     *
     * @return Builder[]|Collection
     */
    public function all()
    {
        if ($this instanceof Cacheable) {
            return $this->cache->remember(
                $this->cacheKey().'.*',
                $this->cacheTTLValue(),
                function () {
                    return $this->get();
                }
            );
        }

        return $this->get();
    }

    /**
     * Returns all models with selected columns.
     *
     * @param  mixed  $columns
     *
     * @return Builder[]|Collection
     */
    public function get(...$columns)
    {
        $columns = Arr::flatten($columns);

        if (count($columns) === 0) {
            $columns = ['*'];
        }

        return $this->model->get($columns);
    }

    /**
     * Finds a model with ID.
     *
     * @param  int|string  $modelId
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function find($modelId)
    {
        if ($this instanceof Cacheable) {
            $model = $this->cache->remember(
                $this->cacheKey().'.'.$modelId,
                $this->cacheTTLValue(),
                function () use ($modelId) {
                    return $this->model->find($modelId);
                }
            );
        } else {
            $model = $this->model->find($modelId);
        }

        if (!$model) {
            $this->throwModelNotFoundException($modelId);
        }

        return $model;
    }

    /**
     * Paginates models.
     *
     * @param  int  $perPage
     *
     * @return Builder[]|Collection|mixed
     */
    public function paginate(int $perPage)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Finds models with "whereIn" condition.
     *
     * @param  string  $column
     * @param  mixed   $values
     *
     * @return Builder[]|Collection
     */
    public function getWhereIn(string $column, $values)
    {
        return $this->model->whereIn($column, $values)->get();
    }

    /**
     * Finds first model with "where" condition.
     *
     * @param  string|array  $column
     * @param  mixed         $value
     *
     * @return \Illuminate\Database\Eloquent\Model|object|null
     */
    public function getWhereFirst($column, $value = null)
    {
        $model = $this->buildGetWhereDelegator($column, $value)->first();
        if (!$model) {
            $this->throwModelNotFoundException();
        }
        return $model;
    }

    /**
     * Build delegator for get where expression
     *
     * @param        $column
     * @param  null  $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildGetWhereDelegator($column, $value = null): Builder
    {
        return is_array($column) ?
            $this->model->where($column) : $this->model->where($column, $value);
    }

    /**
     * Finds first model with "whereIn" condition.
     *
     * @param  string  $column
     * @param  mixed   $values
     *
     * @return Builder|Model|object|null
     */
    public function getWhereInFirst(string $column, $values)
    {
        $model = $this->model->whereIn($column, $values)->first();

        if (!$model) {
            $this->throwModelNotFoundException();
        }

        return $model;
    }

    /**
     * Count models with "where" condition.
     *
     * @param  string|array  $column
     * @param  mixed         $value
     *
     * @return int
     */
    public function count($column, $value = null)
    {
        return $this->buildGetWhereDelegator($column, $value)->count();
    }

    /**
     * Finds models with "where" condition.
     *
     * @param  string|array  $column
     * @param  mixed         $value
     *
     * @return Builder[]|Collection
     */
    public function getWhere($column, $value = null)
    {
        return $this->buildGetWhereDelegator($column, $value)->get();
    }

    /**
     * Check if model existed with "where" condition.
     *
     * @param  string|array  $column
     * @param  mixed         $value
     *
     * @return bool
     */
    public function existed($column, $value = null)
    {
        return $this->buildGetWhereDelegator($column, $value)->exists();
    }

    /**
     * Finds models with "where" condition by order. Default sorted by desc primary key
     *
     * @param  array       $conditions
     * @param  array|null  $sortedBy
     * @param  int|null    $limit
     * @param  int|null    $offset
     *
     * @return Builder[]|Collection
     */
    public function getWhereWithSorted(array $conditions, array $sortedBy = null, ?int $limit = 5000, ?int $offset = 0)
    {
        $delegator = $this->buildGetWhereDelegatorWithOrderBy($conditions, $sortedBy);
        return $delegator->skip($offset)->take($limit)->get();
    }

    /**
     * Build delegator get where expression with sorted by
     *
     * @param  array       $conditions
     * @param  array|null  $sortedBy
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function buildGetWhereDelegatorWithOrderBy(array $conditions, array $sortedBy = null)
    {
        $sortedBy = empty($sortedBy) ? [$this->model->getKeyName() => 'desc'] : $sortedBy;
        $delegator = $this->model->where($conditions);
        foreach ($sortedBy as $key => $val) {
            $delegator = $delegator->orderBy($key, $val);
        }
        return $delegator;
    }

    /**
     * Get a model with "where" condition by order. Default sorted by desc primary key
     *
     * @param  array       $conditions
     * @param  array|null  $sortedBy
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     */
    public function getWhereFirstWithSorted(array $conditions, array $sortedBy = null)
    {
        $delegator = $this->buildGetWhereDelegatorWithOrderBy($conditions, $sortedBy);
        $model = $delegator->first();
        if (!$model) {
            $this->throwModelNotFoundException();
        }
        return $model;
    }
}
