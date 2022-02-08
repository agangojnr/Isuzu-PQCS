<?php

namespace App\Models\querymodels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\querymodels\Traits\QueryModelsRelationship;

class QueryModels extends Model
{
    use HasFactory,QueryModelsRelationship;

      protected $table = 'query_models';


    protected $fillable = [
        'id',
        'querycategory_id',
        'unit_model_id',
        'created_at',
        'updated_at',
       
    ];



}
