<?php

namespace SalamWaddah\RelationParser;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait LoadsRelations
{
    public function loadRelations($model, Request $request, string $loaderParam = 'with', string $counterParam = 'with_count'): void
    {
        $withRelations = $request->filled($loaderParam);
        $withCounts = $request->filled($counterParam);

        if (! $withRelations && ! $withCounts) {
            return;
        }

        $relationMethod = 'loadMissing';
        $counterMethod = 'loadCount';

        if ($model instanceof Builder) {
            $relationMethod = 'with';
            $counterMethod = 'withCount';
        }

        if ($withRelations) {
            $requestedRelations = $request->get($loaderParam);

            $relationsAsArray = array_filter(
                explode(',', $requestedRelations)
            );

            $model->$relationMethod($relationsAsArray);
        }

        if ($withCounts) {
            $requestedCounts = $request->get($counterParam);

            $countersAsArray = array_filter(
                explode(',', $requestedCounts)
            );

            $model->$counterMethod($countersAsArray);
        }
    }
}
