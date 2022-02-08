<?php

namespace App\Models\unitroute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unitroutes extends Model
{
    use HasFactory;

      protected $fillable = [
        'name', 'routing_part', 'route_number','description',
    ];

     protected $table = 'unit_routes';

       protected $hidden = [
        'created_at', 'updated_at',
    ];
}
