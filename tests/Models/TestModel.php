<?php

namespace SalamWaddah\RelationParser\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SalamWaddah\RelationParser\LoadsRelations;

class TestModel extends Model
{
    use LoadsRelations;

    public function relatedModels(): HasMany
    {
        return $this->hasMany(RelatedModel::class);
    }

    public function anotherRelation(): HasMany
    {
        return $this->hasMany(RelatedModel::class);
    }
}
