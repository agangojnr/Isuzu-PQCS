<?php

namespace App\Models\drrtarget;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\drrtarget\Traits\DrrtargetRelationship;

class DrrTarget extends Model
{
    use HasFactory,DrrtargetRelationship;

    protected $table = 'drr_target';


     protected $fillable = [
        'plant_target',
        'target_type',
        'target_name',
        'cv_target',
        'lcv_target',
        'active',
        'fromdate',
        'todate',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
