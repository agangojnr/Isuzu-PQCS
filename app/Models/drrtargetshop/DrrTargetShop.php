<?php

namespace App\Models\drrtargetshop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\drrtargetshop\Traits\DrrtargetshopRelationship;

class DrrTargetShop extends Model
{
    use HasFactory,DrrtargetshopRelationship;

      protected $table = 'drr_target_shop';


     protected $fillable = [
        'target_id',
        'shop_id',
        'target_value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
