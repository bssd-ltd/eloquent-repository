<?php

namespace Bssd\EloquentRepository\Repository\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read Builder|Model $model
 * @mixin \Bssd\EloquentRepository\EloquentRepository
 */
trait CreatesEntity
{
    /**
     * Creates model.
     *
     * @param  mixed  $properties
     *
     * @return Builder|Model
     */
    public function create($properties)
    {
        return $this->model->create($properties);
    }
}
