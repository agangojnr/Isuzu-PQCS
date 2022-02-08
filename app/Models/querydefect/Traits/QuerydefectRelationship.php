<?php

namespace App\Models\querydefect\Traits;
use App\Models\queryanswer\Queryanswer;
use App\Models\routingquery\Routingquery;
use App\Models\querycategory\Querycategory;

use App\Models\shop\Shop;
use App\Models\appuser\Appuser;

/**
 * Class AccountRelationship
 */
trait QuerydefectRelationship
{



     public function getqueries()
    {

      return $this->hasOne(Routingquery::class,'id','routingquery_id');


    }

     public function qshops()
    {

      return $this->hasOne(Shop::class,'id','shop_id');


    }

 public function vehicle()
    {
        return $this->hasOne('App\Models\vehicle_units\vehicle_units','id','vehicle_id');
    }

      public function getqueryanswer()
    {

      return $this->hasOne(Queryanswer::class,'id','query_anwer_id');


    }


    public function corrected()
    {

      return $this->hasOne(Appuser::class,'id','corrected_by');


    }


  


     


}
