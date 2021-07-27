<?php

namespace Bssd\EloquentRepository\Repository\Concerns;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Get next auto increment ID
     *
     * @return int
     */
    public function getAutoIncrementedId(): int
    {
        $inspector = DB::select("SHOW TABLE STATUS LIKE '".$this->model->getTable()."'");
        return $inspector[0]->Auto_increment;
    }

    /**
     * Get next customized ID
     *
     * @param  string  $field
     * @param  int     $length
     * @param  string  $prefix
     *
     * @return string
     * @throws \Exception
     */
    public function getCustomizedId(string $field, int $length, string $prefix): string
    {
        return IdGenerator::generate([
            'table' => $this->model->getTable(),
            'field' => $field,
            'length' => $length,
            'prefix' => $prefix
        ]);
    }
}
