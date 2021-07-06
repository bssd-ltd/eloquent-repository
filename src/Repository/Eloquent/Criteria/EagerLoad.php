<?php

namespace Bssd\EloquentRepository\Repository\Eloquent\Criteria;

use Bssd\EloquentRepository\Repository\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class EagerLoad implements Criterion
{
    /**
     * @var array
     */
    protected $relations;

    /**
     * EagerLoad constructor.
     *
     * @param  mixed  ...$relations
     */
    public function __construct(...$relations)
    {
        $this->relations = Arr::flatten($relations);
    }

    /**
     * @param  Builder|mixed  $model
     *
     * @return Builder|mixed
     */
    public function apply($model)
    {
        return $model->with($this->relations);
    }
}
