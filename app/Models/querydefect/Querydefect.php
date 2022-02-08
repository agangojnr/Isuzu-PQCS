<?php

namespace App\Models\querydefect;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\querydefect\Traits\QuerydefectRelationship;

class Querydefect extends Model
{
    use HasFactory,
        QuerydefectRelationship;

     protected $table = 'query_defects';


     protected $fillable = [
        'query_anwer_id',
        'defect_name',
        'is_defect',
        'repaired',
        'category_id',
        'shop_id',
        'vehicle_id',
        'value_given',
        'is_addition',
        'corrected_by',
        'note',
        'stakeholder',
        'defect_corrected_by',
        'defect_category',
        'routingquery_id',
        'mpa_drr',
        'mpb_drr',
        'mpc_drr',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
