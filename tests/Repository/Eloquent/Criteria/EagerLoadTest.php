<?php

namespace Bssd\EloquentRepository\Tests\Repository\Eloquent\Criteria;

use Illuminate\Support\Facades\DB;
use Bssd\EloquentRepository\Tests\Model;
use Bssd\EloquentRepository\Tests\TestCase;
use Bssd\EloquentRepository\Tests\ModelRelation;
use Bssd\EloquentRepository\Tests\FakeModelRepository;
use Bssd\EloquentRepository\Repository\Eloquent\Criteria\EagerLoad;

class EagerLoadTest extends TestCase
{
    private $repository;

    public function testEagerLoad()
    {
        $model = Model::create(['id' => 5, 'name' => 'model1']);
        ModelRelation::create(['id' => 12, 'model_id' => $model->id]);

        $result = $this->repository->withCriteria(new EagerLoad('hasManyRelation'))->find($model->id);

        $this->assertTrue($result->relationLoaded('hasManyRelation'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE models (id INT, name VARCHAR, deleted_at TIMESTAMP);');
        DB::statement('CREATE TABLE model_relations (id INT, model_id INT);');

        $this->repository = app()->make(FakeModelRepository::class);
    }
}
