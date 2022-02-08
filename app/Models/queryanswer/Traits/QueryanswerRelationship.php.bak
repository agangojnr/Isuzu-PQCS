<?php

namespace App\Models\queryanswer\Traits;
use App\Models\appuser\Appuser;



/**
 * Class AccountRelationship
 */
trait QueryanswerRelationship
{



     public function defects()
    {
        return $this->hasMany('App\Models\querydefect\Querydefect','query_anwer_id','id');
    }

      public function doneby()
    {

      return $this->hasOne(Appuser::class,'id','done_by');


    }

    public function routing()
    {


       return $this->hasOne('App\Models\routingquery\Routingquery','id','query_id');
     


    }

      public function shop()
    {
        return $this->hasOne('App\Models\shop\shop','id','shop_id');
    }

     public function vehicle()
    {
        return $this->hasOne('App\Models\vehicle_units\vehicle_units','id','vehicle_id');
    }


    


}
