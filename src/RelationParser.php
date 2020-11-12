<?php

namespace SalamWaddah\RelationParser;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RelationParser
{
    /**
     * @param Request $request
     * @param         $query
     * @param string  $modelNamespace
     *
     * @return Builder | void
     */
    public static function parse(Request $request, $query, string $modelNamespace)
    {
        if ($request->filled('includes')) {
            $model = new $modelNamespace();
            $relations = $request->get('includes');
            $modelRelations = $model->getAvailableRelations();
            $requestedRelations = new Collection(explode(',', $relations));

            $relationsAsArray = $requestedRelations->map(function ($relation) {
                return Str::camel($relation);
            })->filter(function ($relation) use ($modelRelations) {
                return in_array($relation, $modelRelations, true);
            });

            if ($query instanceof Builder) {
                $query->with($relationsAsArray->toArray());
            } else {
                $query->load($relationsAsArray->toArray());
            }
        }

        if ($query instanceof Builder) {
            return $query;
        }
    }
}
