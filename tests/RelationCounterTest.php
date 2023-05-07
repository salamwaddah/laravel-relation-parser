<?php

namespace SalamWaddah\RelationParser\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use SalamWaddah\RelationParser\LoadsRelations;
use SalamWaddah\RelationParser\Tests\Models\RelatedModel;
use SalamWaddah\RelationParser\Tests\Models\TestModel;

class RelationCounterTest extends TestCase
{
    use LoadsRelations;

    /** @test */
    public function relation_counted_when_it_is_in_request(): void
    {
        $model = TestModel::query()->create();

        // Create a related model
        RelatedModel::query()->create([
            'test_model_id' => $model->getKey(),
        ]);

        $request = new Request([
            'with_count' => 'relatedModels',
        ]);

        $this->loadRelations($model, $request);

        self::assertSame(1, $model->getAttribute('related_models_count'));
    }
}
