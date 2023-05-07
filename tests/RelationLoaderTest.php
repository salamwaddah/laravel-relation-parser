<?php

namespace SalamWaddah\RelationParser\Tests;

use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Http\Request;
use SalamWaddah\RelationParser\LoadsRelations;
use SalamWaddah\RelationParser\Tests\Models\TestModel;

class RelationLoaderTest extends TestCase
{
    use LoadsRelations;

    /** @test */
    public function no_relation_loaded_when_request_has_no_body(): void
    {
        $model = new TestModel();
        $request = new Request();

        $this->loadRelations($model, $request);

        $this->assertFalse($model->relationLoaded('relatedModels'));
    }

    /** @test */
    public function no_relation_loaded_when_request_has_empty_relation(): void
    {
        $model = new TestModel();
        $request = new Request([
            'with' => ',',
        ]);

        $this->loadRelations($model, $request);

        $this->assertFalse($model->relationLoaded('relatedModels'));
    }

    /** @test */
    public function exception_thrown_when_relation_does_not_exist(): void
    {
        $model = new TestModel();
        $request = new Request([
            'with' => 'nonExistingRelation',
        ]);

        $this->expectException(RelationNotFoundException::class);

        $this->loadRelations($model, $request);
    }

    /** @test */
    public function relation_loaded_when_it_is_in_request(): void
    {
        $model = new TestModel();
        $request = new Request([
            'with' => 'relatedModels',
        ]);

        $this->loadRelations($model, $request);

        $this->assertTrue($model->relationLoaded('relatedModels'));
    }

    /** @test */
    public function relation_loaded_using_custom_loader_param(): void
    {
        $model = new TestModel();
        $request = new Request([
            'include' => 'relatedModels',
        ]);

        $this->loadRelations($model, $request, 'include');

        $this->assertTrue($model->relationLoaded('relatedModels'));
    }

    /** @test */
    public function multiple_relations_are_loaded_when_they_are_in_request(): void
    {
        $model = new TestModel();
        $request = new Request([
            // add extra commas to test that they are ignored
            'with' => 'relatedModels,anotherRelation,,,',
        ]);

        $this->loadRelations($model, $request);

        $this->assertTrue($model->relationLoaded('relatedModels'));
        $this->assertTrue($model->relationLoaded('anotherRelation'));
    }

    /** @test */
    public function relation_loaded_when_model_is_query_builder(): void
    {
        $model = TestModel::query();
        $request = new Request([
            'with' => 'relatedModels',
        ]);

        $this->loadRelations($model, $request);

        $this->assertTrue($model->getEagerLoads()['relatedModels'] instanceof \Closure);
    }
}
