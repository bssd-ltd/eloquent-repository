<?php

namespace Bssd\EloquentRepository\Tests;

use Bssd\EloquentRepository\EloquentRepository;

class FakeModelRelationRepository extends EloquentRepository
{
    /**
     * @var string
     */
    protected $entity = ModelRelation::class;
}
