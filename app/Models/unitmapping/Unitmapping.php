<?php

namespace App\Models\unitmapping;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\unitmapping\Traits\UnitMappingRelationship;

class Unitmapping extends Model
{
    use HasFactory,UnitMappingRelationship;

    protected $fillable = [
        'shop_id', 'next_shop_id', 'route_id',
    ];

     protected $table = 'unit_route_mapping';

       protected $hidden = [
        'created_at', 'updated_at',
    ];
}
