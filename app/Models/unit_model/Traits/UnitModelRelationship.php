<?php

namespace App\Models\unit_model\Traits;



/**
 * Class AccountRelationship
 */
trait UnitModelRelationship
{
     public function category()
    {
        return $this->hasOne('App\Models\vehicletype\VehicleType','id','vehicle_type_id');
    }
     

      /*public function model()
    {
        return $this->hasOne('App\Models\unit_model\Unit_model','id','model_id');
    }  */

}
