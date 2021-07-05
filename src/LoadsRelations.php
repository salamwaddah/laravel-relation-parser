<?php

namespace SalamWaddah\RelationParser;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait LoadsRelations
{
    public function loadRelations($model, Request $request, string $loaderParam = 'with'): void
    {
        if (! $request->filled($loaderParam)) {
            return;
        }

        $requestedRelations = $request->get($loaderParam);

        $relationsAsArray = array_filter(
            explode(',', $requestedRelations)
        );

        if ($model instanceof Builder) {
            $model->with($relationsAsArray);
        } else {
            $model->loadMissing($relationsAsArray);
        }
    }
}
