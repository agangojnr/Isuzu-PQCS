<?php

namespace App\Models\Rerouting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rerouting extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'unit_movement_id',
        'rerouting_details_id',
        'from_shop_id',
        'to_shop_id',
        'is_deleted',
        
   
    ];
}
