<?php

namespace App\Models\vehicle_units;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\vehicle_units\Traits\VehicleUnitRelationship;
class vehicle_units extends Model
{
    use HasFactory,VehicleUnitRelationship;

      protected $fillable = [
        'lot_no',
        'vin_no',
        'engine_no',
        'job_no',
        'model_id',
        'total_components',
        'component_moved',
        'color',
        'schedule_date',
        'offline_date',
        'start_date',
        'completion_date',
        'status',
        'route',
        'cabin_cockpit_moved',
        'chasis_moved',
        'sheduled_id',
        'sheduled_batch_no',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
