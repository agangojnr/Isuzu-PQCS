<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\querydefect\Querydefect;
use App\Models\defaultanswer\Defaultanswer;
use Illuminate\Support\Facades\Auth;
use App\Models\queryanswer\Queryanswer;
use App\Models\vehicle_units\vehicle_units;
use Carbon\Carbon;
use App\Http\Controllers\AppHelper;

use DB;

class Defect extends Controller
{
    


     public function qdefects($vehicle_id)
    {

    	$data = Querydefect::where('vehicle_id',$vehicle_id)->where('repaired','No')->with('getqueries','qshops')->get();
    	return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);

    }

    public function loaddefects($defect_id)
    {

    	   $master = array();
        
       
       $data = Querydefect::find($defect_id);
       $master['defects']=$data;
       $master['dafaultoptions']= Defaultanswer::get(['name']);
 return response()->json(['msg' => null, 'data' => $master, 'success' => true], 200);
    }


    public function correctdefect(Request $request)
    {


 if(empty($request->answer) ){

      return response()->json(['msg' => 'OK/NOK Field cannot be empty!!!', 'data' => null , 'success' => false], 200);
      exit;

    }

     /* if($request->weight== "undefined" ||  $request->weight==''  ){

      return response()->json(['msg' => 'Weight  Field cannot be empty!!!', 'data' => null , 'success' => false], 200);
      exit;

    }*/

       $data = Querydefect::find($request->defect_id);

      if($data->repaired== "Yes" ){

      return response()->json(['msg' => 'Defect Already Corrected!!!', 'data' => null , 'success' => false], 200);
      exit;

    }


          $data->repaired='No';
         if($request->answer['name']=='OK'){
            $data->repaired='Yes';
            }

     $data->corrected_by = Auth::id();
     $data->note = $request->remarks;
     //$data->value_given = $request->weight;
     $data->defect_corrected_by = $request->defect_corrected_by;
      if ($request->icon && $request->icon != "undefined") {
      $data->defect_image  = (new AppHelper)->saveBase64($request->icon);
            
    }
     $data->save();

   $exist=$this->checkallcorrected($data->query_anwer_id);
     if(!$exist){
     	//update has error
     $query = Queryanswer::find($data->query_anwer_id);
     $query->has_error = "No";
     $query->save();

     }


return response()->json(['msg' => null, 'data' => $exist, 'success' => true], 200);
    }


    
 public function checkallcorrected($value)
    {
        return  Querydefect::where('query_anwer_id', $value)->where('repaired','No')->exists();
    }


 public function unitswithdefects()
    { 
      $data = vehicle_units::where('status',1)->with('model')->has('defects')->get();
    	return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }


    


    
}
