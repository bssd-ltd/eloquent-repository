<?php

namespace Bssd\EloquentRepository\Tests\Repository\Eloquent\Criteria;

use Illuminate\Support\Facades\DB;
use Bssd\EloquentRepository\Tests\Model;
use Bssd\EloquentRepository\Tests\TestCase;
use Bssd\EloquentRepository\Tests\FakeModelRepository;
use Bssd\EloquentRepository\Repository\Eloquent\Criteria\OrderBy;

class OrderByTest extends TestCase
{
    private $repository;

    public function testOrderBy()
    {
        Model::create(['id' => 5, 'name' => 'abc']);
        Model::create(['id' => 15, 'name' => 'zyx']);

        $result = $this->repository->withCriteria(new OrderBy('name', 'desc'))->get();

        $this->assertEquals(15, $result->first()->id);
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR, deleted_at TIMESTAMP);');

        $this->repository = app()->make(FakeModelRepository::class);
    }
}
