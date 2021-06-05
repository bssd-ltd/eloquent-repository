<?php

namespace Bssd\EloquentRepository\Tests;

use Bssd\EloquentRepository\EloquentRepository;

class FakeModelRepository extends EloquentRepository
{
    /**
     * @var int
     */
    protected $cacheTTL = 500;
    /**
     * @var string
     */
    protected $entity = Model::class;
}
