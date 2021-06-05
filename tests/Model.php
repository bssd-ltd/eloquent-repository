<?php

namespace Bssd\EloquentRepository\Tests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use SoftDeletes;

    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function hasManyRelation()
    {
        return $this->hasMany(ModelRelation::class);
    }

    public function scopeIdGreaterThan10(Builder $builder)
    {
        return $builder->where('id', '>', 10);
    }

    public function scopeIdLessThan20(Builder $builder)
    {
        return $builder->where('id', '<', 20);
    }
}
