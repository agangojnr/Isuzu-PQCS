<?php

namespace App\Models\querycategory\Traits;
use App\Models\queryanswer\Queryanswer;
use App\Models\routingquery\Routingquery;



/**
 * Class AccountRelationship
 */
trait QueryCategoryRelationship
{

 public function shop()
    {
        return $this->hasOne('App\Models\shop\Shop','id','shop_id');
    }
      public function category()
    {
        return $this->hasOne('App\Models\querycategory\Querycategory','id','category_id');
    }

      public function models()
    {
        return $this->hasOne('App\Models\unit_model\Unit_model','id','model_id');
    } 

     public function query_items()
    {
        return $this->hasMany('App\Models\routingquery\Routingquery','category_id','id');
    }


     public function queries()
    {
        return $this->hasMany('App\Models\routingquery\Routingquery','category_id','id')->orderBy('position', 'asc');
    }

    public function querieswithoutothers()
    {
        return $this->hasMany('App\Models\routingquery\Routingquery','category_id','id')->where('quiz_type','!=','others');
    }

     /* public function queryanswers()
    {
         return $this->hasManyThrough(Queryanswer::class,Routingquery::class,'category_id','query_id');
    }*/
	
	 public function query_models()
    {
        return $this->hasMany('App\Models\querymodels\QueryModels','querycategory_id','id');
    }

      public function queryanswers()
    {
        return $this->hasMany('App\Models\queryanswer\Queryanswer','category_id','id');
    }
	
	
	public function getquizmodel()
    {
       
      return $this->belongsToMany('App\Models\unit_model\Unit_model', 'query_models', 'querycategory_id', 'unit_model_id');
    }



}
