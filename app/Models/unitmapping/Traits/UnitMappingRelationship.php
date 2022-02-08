<?php

namespace App\Models\unitmapping\Traits;



/**
 * Class AccountRelationship
 */
trait UnitMappingRelationship
{
     public function shop()
    {
        return $this->hasOne('App\Models\shop\shop','id','shop_id');
    }
      public function next_shop()
    {
        return $this->hasOne('App\Models\shop\shop','id','next_shop_id');
    }
    public function routes()
    {
        return $this->hasOne('App\Models\unitroute\Unitroutes','id','route_id');
    }

     

}
