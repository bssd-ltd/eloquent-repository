<?php

namespace Bssd\EloquentRepository\Repository\Eloquent\Criteria;

use Bssd\EloquentRepository\Repository\Criteria\Criterion;
use Illuminate\Database\Eloquent\Builder;

class Latest implements Criterion
{
    /**
     * @var string
     */
    protected $column;

    /**
     * Latest constructor.
     *
     * @param  string  $column
     */
    public function __construct(string $column = 'id')
    {
        $this->column = $column;
    }

    /**
     * @param  Builder|mixed  $model
     *
     * @return Builder|mixed
     */
    public function apply($model)
    {
        return $model->orderByDesc($this->column);
    }
}
