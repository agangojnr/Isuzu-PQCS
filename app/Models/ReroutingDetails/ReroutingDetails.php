<?php

namespace App\Models\ReroutingDetails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReroutingDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'vehicle_id',
        'user_id',
        'from_route_id',
        'to_route_id',
        'cabin_or_chassis',
        'description',
   
    ];
}
