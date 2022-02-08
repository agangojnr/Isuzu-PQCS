<?php

namespace App\Models\unitmovement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\unitmovement\Traits\UnitmovementRelationship;

class Unitmovement extends Model
{
    use HasFactory,
    UnitmovementRelationship;

      protected $table = 'unit_movements';


     protected $fillable = [
        'current_position',
        'vehicle_id',
        'shop_id',
        'route_id',
        'route_number',
        'datetime_in',
        'datetime_out',
        'current_shop',
        'done_by',
        'std_hrs',
        'appuser_id',
        'swap_id',
        'swap_reference',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
