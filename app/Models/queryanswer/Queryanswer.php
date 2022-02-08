<?php

namespace App\Models\queryanswer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\queryanswer\Traits\QueryanswerRelationship;


class Queryanswer extends Model
{
    use HasFactory,QueryanswerRelationship;

      protected $table = 'query_answers';


     protected $fillable = [
         'job_id',
        'answer',
        'vehicle_id',
        'category_id',
        'shop_id',
        'query_id',
        'has_error',
        'additional_query',
        'done_by',
        'icon',
        'signature',
        'swap_id',
        'swap_reference',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
