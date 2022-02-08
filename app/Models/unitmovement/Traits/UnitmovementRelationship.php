<?php

namespace App\Models\unitmovement\Traits;

use App\Models\unit_model\Unit_model;
use App\Models\vehicle_units\vehicle_units;

/**
 * Class AccountRelationship
 */
trait UnitmovementRelationship
{


	 public function vehicle()
    {
        return $this->hasOne('App\Models\vehicle_units\vehicle_units','id','vehicle_id');
    }

    

     public function shop()
    {
        return $this->hasOne('App\Models\shop\Shop','id','shop_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\appuser\Appuser','id','appuser_id');
    }


      public function adminuser()
    {
        return $this->hasOne('App\Models\user\User','id','appuser_id');
    }


        public function models()
    {
        return $this->hasOneThrough(Unit_model::class,vehicle_units::class,'id','id','vehicle_id','model_id');
    }

    
     

}
