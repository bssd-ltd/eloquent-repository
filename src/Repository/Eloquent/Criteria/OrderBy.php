<?php

namespace Bssd\EloquentRepository\Repository\Eloquent\Criteria;

use Bssd\EloquentRepository\Repository\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class OrderBy implements Criterion
{
    /**
     * @var string
     */
    protected $column;
    /**
     * @var string
     */
    protected $sortBy;

    /**
     * OrderBy constructor.
     *
     * @param  string  $column
     * @param  string  $sortBy
     */
    public function __construct(string $column, string $sortBy)
    {
        $this->column = $column;
        $this->sortBy = $sortBy;
    }

    /**
     * @param  Builder|mixed  $model
     *
     * @return Builder|mixed
     */
    public function apply($model)
    {
        return $model->orderBy($this->column, $this->sortBy);
    }
}
