<?php

namespace App\Http\Controllers\currentstage;
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

use Carbon\Carbon;

class CurrentStageController extends Controller
{
    

    public function currentunitstage()
    {



       if (request()->ajax()) {

             $unitmovement = Unitmovement::where('current_shop', '>', 0)->get();

        return DataTables::of($unitmovement)
              ->addColumn('action', function ($unitmovement) {
                return '
                <button data-href="' . route('moveunit', [$unitmovement->id]) . '" title="Change"  class="btn btn-xs btn-primary edit_unit_button"><i class="mdi mdi-tooltip-edit"></i> Move </button>
                  ';
            })
       ->addColumn('unit_vin', function ($unitmovement) {
                return $unitmovement->vehicle->vin_no;
            })

         ->addColumn('unit_model', function ($unitmovement) {
                return $unitmovement->models->model_name;
            })
       
       ->addColumn('unit_lot', function ($unitmovement) {
                return $unitmovement->vehicle->lot_no;
           })
        ->addColumn('unit_job', function ($unitmovement) {
                return $unitmovement->vehicle->job_no;
           })
          ->addColumn('shop', function ($unitmovement) {
                return $unitmovement->shop->shop_name;
           })

           ->addColumn('datein', function ($unitmovement) {
                return dateTimeFormat($unitmovement->created_at);
           })
          ->addColumn('doneby', function ($unitmovement) {

               
                    switch ($unitmovement->done_by) {
                        case 'Admin':
                             return $unitmovement->done_by.' - '.$unitmovement->adminuser->name;
                            break;
                        case 'inspector':
                             return $unitmovement->done_by.' - '.$unitmovement->user->name;
                            break;
                        case 'stores':
                             return $unitmovement->done_by.' - '.$unitmovement->user->name;
                            break;
                    }
                



               
           })


          ->make(true);
       

    }

        return view('currentstage.current-stage');
    }



      public function moveunit($id)
    {
        

        if (request()->ajax()) {
          
            $shops = Shop::pluck('shop_name', 'id');

            return view('currentstage.moveunit')->with(compact('id','shops'));
        }
    }

     public function saveunitmovement(Request $request)
    {
        

        if (request()->ajax()) {



          $data = $request->only(['movement_id', 'shop_id']);
           $validator = Validator::make($request->all(), [
            'shop_id' => 'bail|required|max:20',
            
            
        ]);

// Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
       return $output;
               exit;                      
    }



          $movement = Unitmovement::find($request->movement_id);
          $vehicle = vehicle_units::find($movement->vehicle_id);

        $nextshop = Unitmapping::where('route_id',$movement->route_id)->where('shop_id',$request->shop_id)->first();

        $std = Std_working_hr::where('model_id',$vehicle->model_id)->where('shop_id',$request->shop_id)->first();

  
        if(empty($nextshop)){

          $output = ['success' => false,
                            'msg' => 'Invalid Route!!!',
                            ];
          return $output;
               exit;
        }

        $checkifmoved=$this->check_unit_moved($movement->vehicle_id,$request->shop_id);
      if($checkifmoved ){

        $output = ['success' => false,
                            'msg' => "Unit already moved to the next shop!!!",
       ];

       return $output;
  exit;
}
       

         $date=now();
        $record['current_position']='InShop';
        $record['datetime_in']=$date;
        $record['vehicle_id']=$movement->vehicle_id;
        $record['route_id']=$movement->route_id;
        $record['route_number']=$movement->route_number;
        $record['shop_id']=$request->shop_id;
        $record['current_shop']=$request->shop_id;
        $record['done_by']='Admin';
        $record['appuser_id']=auth()->user()->id;



        DB::beginTransaction();
         try {

           

       $datas = Unitmovement::create($record);


      

           // update unitmovement
        $movement->current_shop = 0;
        $movement->std_hrs = $std->std_hors;
        $movement->datetime_out = now();
        $movement->save();

          
              // check if defect exist
             $exist=$this->check_defect($request->vehicle_id);
            if($exist && $offlinecheck==1){
              //save drr
        $drrrecord['vehicle_id']=$request->vehicle_id;
        $drrrecord['shop_id']=$shopdefectcaptured;
        $drrrecord['done_by']=Auth::id();
        $drrsave = Drr::create($drrrecord);


       }

       

       DB::commit();

       $output = ['success' => true,
                            'msg' => "Unit Moved  Successfully"
                        ];
      
          } catch (\Exception $e) {
                  \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }
                    
            
            







        }
    

  return $output;
    

}


      public function check_defect($value)
    {
        return  Queryanswer::where('has_error', 'Yes')->where('vehicle_id',$value)->exists();
    }


      public function check_unit_moved($value,$value1)
    {
        return  Unitmovement::where('current_shop', $value1)->where('vehicle_id',$value)->exists();
    }



  }