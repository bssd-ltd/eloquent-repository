<?php

namespace Bssd\EloquentRepository;

use Bssd\EloquentRepository\Repository\Concerns\CreatesEntity;
use Bssd\EloquentRepository\Repository\Concerns\DeletesEntity;
use Bssd\EloquentRepository\Repository\Concerns\SelectsEntity;
use Bssd\EloquentRepository\Repository\Concerns\UpdatesEntity;
use Bssd\EloquentRepository\Repository\Contracts\Repository;
use Bssd\EloquentRepository\Repository\Criteria;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Laravel\Lumen\Application;

class EloquentRepository implements Repository
{
    use SelectsEntity;
    use CreatesEntity;
    use UpdatesEntity;
    use DeletesEntity;

    /**
     * @var Cache
     */
    protected $cache;
    /**
     * @var int
     */
    protected $cacheTTL = 3600;
    /**
     * @var string|null
     */
    protected $entity = null;
    /**
     * @var Builder|Model
     */
    protected $model;
    /**
     * @var Application
     */
    private $application;

    /**
     * EloquentRepository constructor.
     *
     * @param  Application  $application
     * @param  Cache  $cache
     *
     * @throws BindingResolutionException
     */
    public function __construct(Application $application, Cache $cache)
    {
        $this->application = $application;
        $this->cache = $cache;

        if ($this->entity) {
            $this->resolveEntity();
        }
    }

    /**
     * Resolves entity.
     *
     * @throws BindingResolutionException
     */
    private function resolveEntity(): void
    {
        $this->model = $this->application->make($this->entity);
    }

    /**
     * @param  string  $entity
     *
     * @return self
     *
     * @throws BindingResolutionException
     */
    public function setEntity($entity): self
    {
        $this->entity = $entity;
        $this->resolveEntity();

        return $this;
    }

    /**
     * Sets listed criteria for entity.
     *
     * @param  mixed  ...$criteria
     *
     * @return self
     */
    public function withCriteria(...$criteria): self
    {
        $criteria = Arr::flatten($criteria);

        foreach ($criteria as $criterion) {
            /* @var Criteria\Criterion $criterion */
            $this->model = $criterion->apply($this->model);
        }

        return $this;
    }

    /**
     * Removes cache for model.
     *
     * @param  Model  $model
     */
    public function invalidateCache($model): void
    {
        $this->cache->forget(
            $this->cacheKey().'.*'
        );
        $this->cache->forget(
            $this->cacheKey().'.'.$model->id
        );
    }

    /**
     * Defines cache key.
     *
     * @return string
     */
    public function cacheKey(): string
    {
        return $this->model->getTable();
    }

    /**
     * Get cache time-to-live value from property or method if available.
     *
     * @return int
     */
    private function cacheTTLValue(): int
    {
        if (method_exists($this, 'cacheTTL')) {
            return $this->cacheTTL();
        }

        return $this->cacheTTL;
    }

    /**
     * Throws ModelNotFoundException exception.
     *
     * @param  array|int  $ids
     */
    private function throwModelNotFoundException($ids = [])
    {
        throw (new ModelNotFoundException())->setModel(
            get_class($this->model->getModel()),
            $ids
        );
    }
}
