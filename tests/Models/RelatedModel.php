<?php

namespace SalamWaddah\RelationParser\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RelatedModel extends Model
{
    protected $guarded = [];

    public function deeperRelation(): HasMany
    {
        return $this->hasMany(RelatedToRelationModel::class);
    }
}
