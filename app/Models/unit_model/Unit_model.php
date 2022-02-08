<?php

namespace App\Models\unit_model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\unit_model\Traits\UnitModelRelationship;

class Unit_model extends Model
{
    use HasFactory,
    UnitModelRelationship;

         protected $fillable = [
        'model_name',
        'model_number',
        'vehicle_type_id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
