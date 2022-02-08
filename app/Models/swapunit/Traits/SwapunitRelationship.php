<?php


namespace App\Models\swapunit\Traits;



/**
 * Class AccountRelationship
 */
trait SwapunitRelationship
{


	 public function fromshop()
    {
        return $this->hasOne('App\Models\shop\Shop','id','from_shop_id');
    }

    

     public function toshop()
    {
        return $this->hasOne('App\Models\shop\Shop','id','to_shop_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\user\User','id','done_by');
    }

    public function fromvehicle()
    {
        return $this->hasOne('App\Models\vehicle_units\vehicle_units','id','swap_unit');
    }

    public function tovehicle()
    {
        return $this->hasOne('App\Models\vehicle_units\vehicle_units','id','swap_with_unit');
    }


  

      

    
     

}
