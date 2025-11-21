<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

trait IdentifiesModel
{
    protected function identifyModel(string $type, int $id): ?Model
    {
        $modelClass = collect(Relation::morphMap())->get($type);
        return $modelClass::findOrFail($id);
    }
}
