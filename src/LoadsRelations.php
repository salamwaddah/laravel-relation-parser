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
            $counts = $this->parseRelations($request->get($counterParam));

            $directCounts = [];
            $nestedCountLoads = [];

            foreach ($counts as $count) {
                if (!str_contains($count, '.')) {
                    $directCounts[] = $count;
                    continue;
                }

                [$parent, $nested] = explode('.', $count, 2);

                $nestedCountLoads[$parent] = function ($query) use ($nested) {
                    $query->withCount($nested);
                };
            }

            if ($directCounts) {
                $model->$counterMethod($directCounts);
            }

            if ($nestedCountLoads) {
                if ($model instanceof Builder) {
                    $model->with($nestedCountLoads);
                } else {
                    $model->loadMissing($nestedCountLoads);
                }
            }
        }
    }
    protected function parseRelations($value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $value))));
    }
}
