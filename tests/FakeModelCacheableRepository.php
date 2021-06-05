<?php

namespace Bssd\EloquentRepository\Tests;

use Bssd\EloquentRepository\EloquentRepository;
use Bssd\EloquentRepository\Repository\Contracts\Cacheable;

class FakeModelCacheableRepository extends EloquentRepository implements Cacheable
{
    /**
     * @var string
     */
    protected $entity = Model::class;

    public function cacheTTL(): int
    {
        return 1000;
    }
}
