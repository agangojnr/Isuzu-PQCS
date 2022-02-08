<?php

namespace App\Models\scheduledbatch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\scheduledbatch\Traits\ScheduledBatchRelationship;


class ScheduledBatch extends Model
{
    use HasFactory,ScheduledBatchRelationship;

          protected $table = 'sheduled_batch';


    protected $fillable = [
        'id',
        'bratch_no',
        'bach_date',
        'created_at',
        'updated_at',
       
    ];
}
