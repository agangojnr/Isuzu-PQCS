<?php

namespace App\Http\Controllers\checksheet;

use App\Http\Controllers\Controller;
use App\Models\unitmovement\Unitmovement;
use App\Models\shop\Shop;
use App\Models\queryanswer\Queryanswer;
use App\Models\drr\Drr;
use Illuminate\Support\Facades\Auth;
use App\Models\std_working_hr\Std_working_hr;
use App\Models\unitmapping\Unitmapping;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\vehicle_units\vehicle_units;
use App\Models\querycategory\Querycategory;
use App\Models\querydefect\Querydefect;

use Carbon\Carbon;

class CheckSheetController extends Controller
{
    


 public function markedunit()
    {

$units = vehicle_units::has('answers')->get();

/*
if (request()->ajax()) {

	$unit = vehicle_units::has('answers')->get();



        return DataTables::of($unit)



         ->addColumn('action', function ($unit) {
              return '
              <a title="Change"  class="btn btn-xs btn-primary edit_unit_button"  href="' . route('checkmarkedsheet', [$unit->id]) . '"><i class="mdi mdi-eye"></i> Check</a>
                  ';
            })

         ->addColumn('model_id', function ($unit) {
                return $unit->model->model_name;
            })

          ->addColumn('total_queries', function ($unit) {
          	//$rows = Querycategory::whereJsonContains('model_id',  ['model_value' => ''.$unit->model_id.''])->get();
            $rows = Querycategory:: where('model_id', 'like',  '%' . $unit->model_id .'%')->get();
           
            $total_queries=0;
            foreach ($rows as $row ) {
             $total_queries+=$row->queries_count;
                
            }
                return $total_queries;
            })



           ->addColumn('answered', function ($unit) {

 	           $total_answered = Queryanswer::where('vehicle_id',$unit->id)->count();
                return $total_answered;
            })


            ->addColumn('correct_answered', function ($unit) {

 	           $correct_answered = Queryanswer::where('vehicle_id',$unit->id)->where('has_error','No')->count();
                return $correct_answered;
            })



             ->addColumn('percentage', function ($unit) {
             	$rows = Querycategory::whereJsonContains('model_id',  ['model_value' => ''.$unit->model_id.''])->get();
            $total_queries=0;
            foreach ($rows as $row ) {
             $total_queries+=$row->queries_count;
                
            }

 	           $correct_answered = Queryanswer::where('vehicle_id',$unit->id)->where('has_error','No')->count();
           $total_answered = Queryanswer::where('vehicle_id',$unit->id)->count();
 	           $prc=(($total_answered-$correct_answered)/$total_answered)*100;

            
 	          
                        $status='<span class="badge badge-danger">Fail</span>'; 
 	           if($prc==0){
               $status='<span class="badge badge-success">Pass</span>';
     
 	           	
                 
 	           }


                return round($prc).'% '.$status ;
            })


            
         
         

         

          ->addColumn('status', function ($unit) {

            $total_answered = Queryanswer::where('vehicle_id',$unit->id)->count();
               

          	if($unit->status==1 || $total_answered>0 ){
                return'<span class="badge badge-warning">In Progress</span>';
          	}else if($unit->status==2){
          		return'<span class="badge badge-success">Completed</span>';

          	}
            })->rawColumns(['status', 'percentage','action'])

        
           

         
        


          ->make(true);
       

    }*/

        return view('markedunit.index')->with(compact('units'));;


    }



    public function checkmarkedsheet($id)
    {


         $vehicle = vehicle_units::find($id);
         $model_id=$vehicle->model_id;

         $vid=$vehicle->id;

          $shops = Shop::with(['querycategory.query_items','querycategory.query_items.quizanswers'=> function ($query) use( $vid) {
    $query->where('vehicle_id', $vid);
},'querycategory.query_items.quizanswers.defects'=> function ($query) use( $vid) {
    $query->where('vehicle_id', $vid);
 },'querycategory'=> function ($query) use( $model_id) {
     $query->where('model_id', 'like',  '%' . $model_id .'%');
 },'querycategory.query_items.quizanswers.doneby'])->get();

      return view('markedunit.checkmarkedsheet')->with(compact('shops','vehicle'));
    }



     public function checkdefects($id,$vid,$shop_id)
    {

         $quiz = Queryanswer::find($id);
         $vehicle = vehicle_units::find($vid);
         $shop=Shop::find($shop_id);
        $defects = Querydefect::where('query_anwer_id',$id)->with(['getqueryanswer.routing.category'])->get();

      return view('markedunit.defect-list')->with(compact('quiz','defects','vehicle','shop'));
    }



     public function changedefect($id)
    {
        

        if (request()->ajax()) {
          
             $defects = Querydefect::where('id',$id)->with(['getqueryanswer.routing.category'])->first();

            return view('markedunit.correctdefect')->with(compact('id','defects'));
        }
    }
    
    

}
