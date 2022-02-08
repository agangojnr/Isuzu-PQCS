<?php

namespace App\Models\shop\Traits;



/**
 * Class AccountRelationship
 */
trait ShopQueryRelationship
{
     public function querycategory()
    {
        return $this->hasMany('App\Models\querycategory\Querycategory','shop_id', 'id');
    }



    public function unitmovement()
    {
        return $this->hasMany('App\Models\unitmovement\Unitmovement','shop_id', 'id');
    }




 public function drrtargetshop()
    {
        return $this->hasOne('App\Models\drrtargetshop\DrrTargetShop','shop_id', 'id');
    }

      

}
