<?php

namespace App\Models\vehicle_units\Traits;



/**
 * Class AccountRelationship
 */
trait VehicleUnitRelationship
{
     public function model()
    {
        return $this->hasOne('App\Models\unit_model\Unit_model','id','model_id');
    }
     

      public function defects()
    {
        return $this->hasOne('App\Models\querydefect\Querydefect','vehicle_id','id');
    }



      public function answers()
    {
        return $this->hasMany('App\Models\queryanswer\Queryanswer','vehicle_id','id');
    }

     public function unitmovement()
    {
        return $this->hasMany('App\Models\unitmovement\Unitmovement','vehicle_id','id');
    }
     

      /*public function model()
    {
        return $this->hasOne('App\Models\unit_model\Unit_model','id','model_id');
    }  */

}
