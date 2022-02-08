<?php

namespace App\Models\drr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\drr\Traits\DrrRelationship;
use Illuminate\Notifications\Notifiable;
//use OwenIt\Auditing\Contracts\Auditable;
//use OwenIt\Auditing\Auditable as AuditableTrait;

class Drr extends Model 
{
    use HasFactory,DrrRelationship;

     protected $table = 'drr_report';


     protected $fillable = [
        'vehicle_id',
        'shop_id',
        'is_app_or_system',
        'done_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
