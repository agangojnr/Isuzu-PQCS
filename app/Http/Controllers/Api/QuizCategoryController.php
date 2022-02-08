<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\querycategory\Querycategory;

use App\Models\queryanswer\Queryanswer;
use App\Models\querydefect\Querydefect;
use App\Models\unitmapping\Unitmapping;
use App\Models\unitmovement\Unitmovement;
use App\Models\shop\Shop;

class QuizCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

        public function quizcategory($id,$model_id,$vid)
    {
       
          $master=array();  
       

       //Queries without Others array
       $category_array =Querycategory::where('shop_id',$id)->where('quiz_type','!=','others')->whereHas('query_models',function ($q)  use ($model_id)  {
        $q->where('unit_model_id', $model_id);
        })->pluck('id');

     

    
       $all_rows  = Querycategory::where('shop_id',$id)->where('quiz_type','!=','others')->whereIn('id', $category_array )->get();
    
            $total_queries=0;      
            foreach ($all_rows as $row ) {
              $total_queries+=$row->queries_count;
                 
             }


                 //All Queriess array
       $all_category_array =Querycategory::where('shop_id',$id)->whereHas('query_models',function ($q)  use ($model_id)  {
        $q->where('unit_model_id', $model_id);
        })->pluck('id');


             $rows  = Querycategory::where('shop_id',$id)->whereIn('id', $all_category_array )->with(['queryanswers'=>function ($q)  use ($vid)  {
                $q->where('vehicle_id', $vid);
            
            }])->get();

             $master['category'] = $rows;    
    
         $total_answered = Queryanswer::where('vehicle_id',$vid)->where('shop_id',$id)->count();
  

        $master['datacheck']=0;
         if($total_answered >=$total_queries){
        $master['datacheck']=1;
        }

       $master['defect_exist']=$this->check_query_defect($vid);
       $defects=Querydefect::with(['getqueryanswer.doneby','getqueryanswer.routing.category'])->where('vehicle_id', $vid)->where('repaired','No')->get();
       $difectlist=[];
       if(!empty($defects)){
        foreach($defects as $defect){
            $difectlist[]=$defect->getqueryanswer->routing->query_name.' ['.$defect->defect_name.']';
        }

       }


       $movement = Unitmovement::where('vehicle_id',$vid)->where('shop_id',$id)->where('current_shop',$id)->first();

                //check next shop
       $nextshop = Unitmapping::where('route_id',$movement->route_id)->where('shop_id',$id)->first();

        $shopdetails = Shop::where('id', $nextshop->next_shop_id)->OrWhere('id', $id)->get();


            $offlinecheck=0;
              $shopdefectcaptured=0;
            foreach ($shopdetails as $shopdetail ) {

                if($shopdetail->offline==1){
                 $offlinecheck=1;
                 $shopdefectcaptured=$shopdetail->id;
                }
            
                
            } 


$master['total_answered']=$total_answered;

$master['total_queries']=$total_queries;

$master['offlinecheck']=$offlinecheck;
       
$master['defect_list']=implode(",", $difectlist);

return response()->json(['msg' => null, 'data' => $master, 'success' => true], 200);


    }

      public function check_query_defect($value)
    {
        return  Querydefect::where('vehicle_id', $value)->where('repaired','No')->exists();
    }

    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
