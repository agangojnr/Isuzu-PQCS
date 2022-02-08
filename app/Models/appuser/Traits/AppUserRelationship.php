<?php

namespace App\Models\appuser\Traits;



/**
 * Class AppUserRelationship
 */
trait AppUserRelationship
{
     public function shop()
    {
        return $this->hasOne('App\Models\shop\Shop','id','shop_id');
    }
      

}
