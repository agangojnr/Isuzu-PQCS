<?php

namespace App\Models\scheduledbatch\Traits;



/**
 * Class AccountRelationship
 */
trait ScheduledBatchRelationship
{
     public function production_target()
    {
        return $this->hasMany('App\Models\productiontarget\ProductionTarget','sheduled_batch_id','id');
    }

    


    
  

}
