<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use MongoDB\Laravel\Eloquent\Model as MongoModel;

// Define custom dynamic base model class for MongoDB and standard SQL fallback
if (!class_exists(\App\Models\BaseModel::class)) {
    if (env('DB_CONNECTION') === 'mongodb' && class_exists(\MongoDB\Driver\Manager::class)) {
        class_alias(MongoModel::class, \App\Models\BaseModel::class);
    } else {
        class_alias(EloquentModel::class, \App\Models\BaseModel::class);
    }
}

// Dummy class definition so IDEs understand it
if (false) {
    class BaseModel extends EloquentModel {}
}
