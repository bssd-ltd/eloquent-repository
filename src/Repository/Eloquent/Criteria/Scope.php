<?php

namespace Bssd\EloquentRepository\Repository\Eloquent\Criteria;

use Bssd\EloquentRepository\Repository\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Scope implements Criterion
{
    /**
     * @var array
     */
    protected $scopes;

    /**
     * Scope constructor.
     *
     * @param  mixed  ...$scopes
     */
    public function __construct(...$scopes)
    {
        $this->scopes = Arr::flatten($scopes);
    }

    /**
     * @param  Builder|mixed  $model
     *
     * @return Builder|mixed
     */
    public function apply($model)
    {
        foreach ($this->scopes as $scope) {
            $model = $model->{$scope}();
        }

        return $model;
    }
}
