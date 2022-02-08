<?php

namespace App\Models\routingquery\Traits;



/**
 * Class AccountRelationship
 */
trait RoutingQueryRelationship
{
     public function shop()
    {
        return $this->hasOne('App\Models\shop\shop','id','shop_id');
    }
      public function category()
    {
        return $this->hasOne('App\Models\querycategory\Querycategory','id','category_id');
    }

      public function models()
    {
        return $this->hasOne('App\Models\unit_model\Unit_model','id','model_id');
    }  

    public function quizanswers()
    {
        return $this->belongsTo('App\Models\queryanswer\Queryanswer','id','query_id');
    }  


     public function getanswer()
    {
        return $this->hasOne('App\Models\queryanswer\Queryanswer','query_id','id');
    
    }


      public function getanswercount()
    {
        return $this->hasMany('App\Models\queryanswer\Queryanswer','query_id','id');
    
    }

}
