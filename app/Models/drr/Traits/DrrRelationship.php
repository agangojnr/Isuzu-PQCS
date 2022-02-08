<?php

namespace App\Models\drr\Traits;
use App\Models\appuser\Appuser;


/**
 * Class AccountRelationship
 */
trait DrrRelationship
{



    

 public function vehicle()
    {
        return $this->hasOne('App\Models\vehicle_units\vehicle_units','id','vehicle_id');
    }

 public function shop()
    {
        return $this->hasOne('App\Models\shop\Shop','id','shop_id');
    }

    public function doneby()
    {

      return $this->hasOne(Appuser::class,'id','done_by');


    }


   

  


     


}
