<?php

namespace App\Models\querymodels\Traits;



/**
 * Class AccountRelationship
 */
trait QueryModelsRelationship
{
    
     

      public function getquery()
    {

    	return $this->hasOne('App\Models\querycategory\Querycategory','id','querycategory_id');


       
    }  

     



   

}
