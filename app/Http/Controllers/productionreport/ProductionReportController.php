<?php

namespace App\Http\Controllers\productionreport;
use App\Http\Controllers\Controller;
use App\Models\unitmovement\Unitmovement;
use App\Models\shop\Shop;
use App\Models\unit_model\Unit_model;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\queryanswer\Queryanswer;
use App\Models\querydefect\Querydefect;
use App\Models\float_settings\FloatSetting;

use App\Models\drr\Drr;
use App\Models\drrtarget\DrrTarget;
use App\Models\drrtargetshop\DrrTargetShop;
use Illuminate\Http\Request;
use App\Models\vehicle_units\vehicle_units;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DrrExport;
use App\Exports\DrlExport;


class ProductionReportController extends Controller
{
    

    public function currentunitstage()
    {



       if (request()->ajax()) {

             $unitmovement = Unitmovement::where('current_shop', '>', 0)->get();

        return DataTables::of($unitmovement)
              ->addColumn('action', function ($unitmovement) {
                return '
                <button data-href="' . route('moveunit', [$unitmovement->id]) . '" title="Change"  class="btn btn-xs btn-primary edit_unit_button"><i class="mdi mdi-tooltip-edit"></i> Change </button>
                   &nbsp;
                <a href="' . route('moveunit', [$unitmovement->id]) . '" title="Delete"  class="btn btn-xs btn-danger delete-query "><i class="mdi mdi-delete"></i> Delete </a>';
            })
       ->addColumn('unit_vin', function ($unitmovement) {
                return $unitmovement->vehicle->vin_no;
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
                return $unitmovement->user->name;
           })


     ->make(true);
       

    }

        return view('production_report.current-stage');
    }



      public function moveunit($id,$from,$to)
    {
        

        if (request()->ajax()) {
          
            $shops = Shop::pluck('shop_name', 'id');
            return view('production_report.moveunit')->with(compact('id','shops'));
        }
    }


       public function drl($section,$period,$date)
    {

      $section=decrypt_data($section);
      $period=decrypt_data($period);
      $date=decrypt_data($date);

    
     


//plant section
      if($section=='plant'){

        //today

        if($period=='today'){


    
       
      


        
          $heading = $heading='DAILY  DIRECT RUN LOSS RESULTS FOR '. date("d F Y", strtotime($date)) ;
          $start=date_for_database($date);
          $first_of_month=Carbon::createFromFormat('Y-m-d', $start)->startOfMonth();
          $first_of_month=$first_of_month->format("Y-m-d");

          

     
     //Units Produced per month

     //$vehicles = vehicle_units::groupBy('lot_no','model_id')->where('sheduled_month', $first_of_month)->get(['lot_no','model_id']);

     $current_shop=0;
     $vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop,$start) {
      $query->where('current_shop', $current_shop);
      $query->where('datetime_out', $start);

      return $query;
})->get([ 'lot_no','model_id']);




     $shops = Shop::where('check_point','=','1')->orderBy('group_order','asc')->get();
     unset($shops[0],$shops[5],$shops[7],$shops[10]);


    

     $drl_arr = [];
        
     $unit_count = [];
      $i=1;
    $vehicleid = [];

    $plant_defect=0;
    foreach($vehicles as $vehicle){
      $modelid = $vehicle->model_id;
      $lot_no = $vehicle->lot_no;
      $shop_id_array=array();
      foreach($shops as $shop){
          $shopid = $shop->id;

          $shop_array=Shop::where('group_shop',$shopid)->pluck('id')->all();
       

          
            $wq = compact('modelid', 'lot_no');
            $drl_arr[$modelid][$lot_no][$shopid]['units'] = Unitmovement::where([['shop_id', '=', $shopid], ['current_shop', '=', 0]])->where('datetime_out',$start)->whereHas('vehicle',function ($query) use( $wq) {
             $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
  })->count();
  
  $vehicle_array=Unitmovement::where([['shop_id', '=', $shopid], ['current_shop', '=', 0]])->where('datetime_out',$start)->whereHas('vehicle',function ($query) use( $wq) {
    $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
  })->pluck('vehicle_id')->all();
  
       $drl_arr[$modelid][$lot_no][$shopid]['defects'] = Querydefect::whereIn('shop_id',$shop_array)->whereIn('vehicle_id',$vehicle_array)->where([['is_defect', '=', 'Yes']])->count();
  
      // $plant_defect+= $drl_arr[$modelid][$lot_no][$shopid]['defects'];


    
         
         
      }
      
  }



//dd( $vehicles);
  

  $data = array(
   // 'target'=>$target, 
    'heading'=>$heading, 
    'shops'=>$shops,
    'vehicles'=>$vehicles,
    'drl_arr'=>$drl_arr,
    'unit_count'=>$unit_count,
    'section'=>encrypt_data($section),
    'period'=>encrypt_data($period),
    'date'=>encrypt_data($date),
    'plant_defect'=>$plant_defect,
    
   // 'plant_units'=>$plant_units,
    //'plant_defect'=>$plant_defect,
    //'plant_target'=>$plant_target,
    //'pant_drl'=>$pant_drl,
    //'target_name'=>$target_name,
    
    //'from'=>$from,
    //'to'=>$to,
   // 'target_id'=>$target_id,
    //'vehicle_models'=> $vehicle_models,
  
    
    
);


return view('productionreport.drl-month-todate')->with($data);

        }

        //this month

        if($period=='month_to_date'){

          $start=Carbon::createFromFormat('F Y', $date)->startOfMonth();
          $start=$start->format("Y-m-d");
          
          $end=Carbon::createFromFormat('F Y', $date)->endOfMonth();
          $end=$end->format("Y-m-d");

     
          $today=Carbon::now();

          $endtwo=$today->format("Y-m-d H:i:s");

        

     /*$target = DrrTarget::where('target_type','Drr')->pluck('target_name', 'id');
     
      $target_details = DrrTarget::where('active', 'Active')->where('target_type','Drr')->first();
     
     $target_name = $target_details->target_name;*/



               
     
     
     $heading='MTD DRL RESULTS FOR '.$date;

     
     //Units Produced per month

     $vehicles = vehicle_units::groupBy('lot_no','model_id')->where('sheduled_month', $start)->get(['lot_no','model_id']);
     $shops = Shop::where('check_point','=','1')->distinct('group_shop')->orderBy('group_order','asc')->get();
     $shopcount = Shop::where('check_point','=','1')->distinct('group_shop')->orderBy('group_order')->count();


     $drl_arr = [];
        
     $unit_count = [];
      $i=1;
    $vehicleid = [];

$plant_defect=0;
    foreach($vehicles as $vehicle){
      $modelid = $vehicle->model_id;
      $lot_no = $vehicle->lot_no;
      foreach($shops as $shop){
          $shopid = $shop->id;

          $shop_array=Shop::where('group_shop',$shopid)->pluck('id')->all();

          $wq = compact('modelid', 'lot_no');
          $drl_arr[$modelid][$lot_no][$shopid]['units'] = Unitmovement::where([['shop_id', '=', $shopid], ['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
           $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();

$vehicle_array=Unitmovement::where([['shop_id', '=', $shopid], ['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->pluck('vehicle_id')->all();

     $drl_arr[$modelid][$lot_no][$shopid]['defects'] = Querydefect::whereIn('shop_id',$shop_array)->whereIn('vehicle_id',$vehicle_array)->where([['is_defect', '=', 'Yes']])->count();

     $plant_defect+=$drl_arr[$modelid][$lot_no][$shopid]['defects'] ;
         
         
      }
      
  }




 // dd($drl_arr);

  $data = array(
   // 'target'=>$target, 
    'heading'=>$heading, 
    'shops'=>$shops,
    'vehicles'=>$vehicles,
    'shopcount'=>$shopcount,
    'drl_arr'=>$drl_arr,
    'unit_count'=>$unit_count,
    'section'=>encrypt_data($section),
    'period'=>encrypt_data($period),
    'date'=>encrypt_data($date),
    'plant_defect'=>$plant_defect,
    
   // 'plant_units'=>$plant_units,
    //'plant_defect'=>$plant_defect,
    //'plant_target'=>$plant_target,
    //'pant_drl'=>$pant_drl,
    //'target_name'=>$target_name,
    
    //'from'=>$from,
    //'to'=>$to,
   // 'target_id'=>$target_id,
    //'vehicle_models'=> $vehicle_models,
  
    
    
);


return view('productionreport.drl-month-todate')->with($data);

        }

        //today

      }

//end plant section
      




      //total Units ofllined 

      //$vehicles = vehicle_units::groupBy('lot_no','model_id')->where('sheduled_month', $start)->get();
    
     $startDate = Carbon::now(); //returns current day
     $firstDay = $startDate->firstOfMonth(); 
     $endDay = $startDate->endOfMonth(); 
     $start=$firstDay->format("Y-m-d");
     $end=$endDay->format("Y-m-d");

     //dd($start);







  

/*


     $offlined_unit = Unitmovement::whereBetween('datetime_out',[$start,$end])->where('current_shop',0)->where('shop_id', 8)
     ->orWhere(function ($query) {
            $query->where('shop_id', 10)
            ->where('shop_id', 13);
  })->count();

  

    $defects_array = Unitmovement::whereBetween('datetime_out',[$start,$end])->where('current_shop',0)->where('shop_id', 8)
    ->orWhere(function ($query) {
           $query->where('shop_id', 10)
           ->where('shop_id', 13);
 })->pluck('vehicle_id')->all();



    $total_defects = Querydefect::wherein('vehicle_id', $defects_array)->count();

$master=array();
$drl=0;
if($offlined_unit>0){
  $drl=round($total_defects/$offlined_unit);
}
$master['total_offlined_units']= $offlined_unit;
$master['defects']= $total_defects;
$master['drl']=$drl ;

*/













      

      
      



      if($section=='this_month'){

     






     

     
    
     


     $drl_arr = [];
        
     $unit_count = [];
      $i=1;
      $i=1;
    $vehicleid = [];
         foreach($vehicles as $vehicle){
      $modelid = $vehicle->model_id;
      $lot_no = $vehicle->lot_no;
      foreach($shops as $shop){
          $shopid = $shop->id;

          //units produced per shop
         // ->pluck('vehicle_id')->all();


          $wq = compact('modelid', 'lot_no');
          $drl_arr[$modelid][$lot_no][$shopid]['units'] = Unitmovement::where([['shop_id', '=', $shopid], ['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
           $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();

//units defects


           $drl_arr[$modelid][$lot_no][$shopid]['defects'] = Querydefect::where([['shop_id', '=', $shopid], ['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->whereHas('vehicle',function ($query) use( $wq) {
$query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


         
         
      }
      
  }






  $target=90;
  $plant_units=0;

  $plant_defect=0;
  $plant_target=0;
  $pant_drl=0;

  $vehicle_models = vehicle_units::groupBy('lot_no','model_id')->where('sheduled_month','2022-01-01')->get();
  $data = array(
    'target'=>$target, 
    'heading'=>$heading, 
    'shops'=>$shops,
    'vehicles'=>$vehicles,
    'shopcount'=>$shopcount,
    'drl_arr'=>$drl_arr,
    'unit_count'=>$unit_count,
    'plant_units'=>$plant_units,
    'plant_defect'=>$plant_defect,
    'plant_target'=>$plant_target,
    'pant_drl'=>$pant_drl,
    'target_name'=>$target_name,
    'section'=>encrypt_data($section),
    //'from'=>$from,
    //'to'=>$to,
   // 'target_id'=>$target_id,
    'vehicle_models'=> $vehicle_models,
  
    
    
);


return view('productionreport.drl-month-todate')->with($data);



      }



    
    
//get units and group by lot and model number
      $current_shop=0;
      $vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                       $query->where('current_shop', $current_shop);
      })->get();
      $shops = Shop::where('check_point','=','1')->get();








    

      

      $vehicle_models = vehicle_units::groupBy('lot_no','model_id')->where('sheduled_month','2022-01-01')->get();


  
       $target = DrrTarget::where('target_type','Drl')->pluck('target_name', 'id');

           $shops = Shop::where('check_point','=','1')->get();
           $section=decrypt_data($section);

      if($section=='month_to_date'){

$target_details = DrrTarget::where('active', 'Active')->where('target_type','Drl')->first();


$target_name = $target_details->target_name;
            


$heading='MONTH TO DATE DIRECT RUN LOSS RESULTS FOR '.date('F Y');
$today=Carbon::now();
$startDate = Carbon::now(); //returns current day
$firstDay = $startDate->firstOfMonth(); 
$end=$today->format("Y-m-d");
$start=$firstDay->format("Y-m-d");


$endtwo=$today->format("Y-m-d H:i:s");

$starttwo=$firstDay->format("Y-m-d 00:00:00");





          // $models = Unit_Model::All();
          //$shopnames = Shop::where('inspector','=','1')->distinct()->get('report_name');

           //$current_shop=0;


           $vehicles = vehicle_units::groupBy('lot_no','model_id')->where('sheduled_month',$start)->get();



//$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
               //  $query->where('current_shop', $current_shop);
//})->get();



          $shops = Shop::where('check_point','=','1')->get();




           $shopcount = Shop::where('check_point','=','1')->distinct()->count();

           $drr_arr = [];
        
           $unit_count = [];
            $i=1;
            $i=1;
          $vehicleid = [];
               foreach($vehicles as $vehicle){
            $modelid = $vehicle->model_id;
            $lot_no = $vehicle->lot_no;
            foreach($shops as $shop){
                $shopid = $shop->id;

                $wq = compact('modelid', 'lot_no');
                $drr_arr[$modelid][$lot_no][$shopid]['units'] = Unitmovement::where([['shop_id', '=', $shopid], ['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
                 $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


                 $drr_arr[$modelid][$lot_no][$shopid]['defects'] = Querydefect::where([['shop_id', '=', $shopid], ['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->whereHas('vehicle',function ($query) use( $wq) {
    $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


               
               
            }
            
        }




     foreach($shops as $shoprow){
                $shop_id = $shoprow->id;
          $unit_count[$shop_id]['total_units'] = Unitmovement::where([['shop_id', '=', $shop_id], ['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->count();
          $shop_id = $shoprow->id;

            $unit_count[$shop_id]['total_defects'] = Querydefect::where([['shop_id', '=', $shop_id], ['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->count();

            if($unit_count[$shop_id]['total_units']==0){
              $midscore=0;

           }else{
            $midscore=($unit_count[$shop_id]['total_defects'] / $unit_count[$shop_id]['total_units'])*100;
           }

            $unit_count[$shop_id]['mdiscore']=round($midscore,2);

             $unit_count[$shop_id]['targetscore']= DrrTargetShop::where([['shop_id', '=', $shop_id], ['target_id', '=', $target_details->id]])->value('target_value');

               
            }

  $plant_units = Unitmovement::where([['current_shop', '=', 0]])->where('shop_id','8')->orWhere('shop_id','9')->orWhere('shop_id','13')->whereBetween('datetime_out',[$start,$end])->count();//change to offline shop

$plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->count();
$plant_target = $target_details->plant_target;
$pant_drl=0;
if($plant_units>0){
$pant_drl=round(($plant_defect/$plant_units),2);
}


      $data = array(
            'target'=>$target, 
            'heading'=>$heading, 
            'shops'=>$shops,
            'vehicles'=>$vehicles,
            'shopcount'=>$shopcount,
            'drr_arr'=>$drr_arr,
            'unit_count'=>$unit_count,
            'plant_units'=>$plant_units,
            'plant_defect'=>$plant_defect,
            'plant_target'=>$plant_target,
            'pant_drl'=>$pant_drl,
            'target_name'=>$target_name,
            'section'=>encrypt_data($section),
            'from'=>$from,
            'to'=>$to,
            'target_id'=>$target_id,
            'vehicle_models'=> $vehicle_models,
          
            
            
        );


       return view('productionreport.drl-month-todate')->with($data);
        
 }elseif ($section=='daily') {


  $target_details = DrrTarget::find(decrypt_data($target_id));
  $target_name = $target_details->target_name;

$originalDate = decrypt_data($from);
$date=date_for_database(decrypt_data($from));





          $originalDate = decrypt_data($from);
          $heading = $heading='DAILY  DIRECT RUN LOSS RESULTS FOR '. date("d F Y", strtotime($originalDate)) ;
          $date=date_for_database(decrypt_data($from));

           //$models = Unit_Model::All();
          //$shopnames = Shop::where('inspector','=','1')->distinct()->get('report_name');


                 $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();





          $shops = Shop::where('check_point','=','1')->get();
           $shopcount = Shop::where('check_point','=','1')->distinct()->count();

           $drr_arr = [];
           $unit_count = [];
            $i=1;
        
          
               foreach($vehicles as $vehicle){
            $modelid = $vehicle->model_id;
            $lot_no = $vehicle->lot_no;
            foreach($shops as $shop){
                $shopid = $shop->id;
                $wq = compact('modelid', 'lot_no');
                  $drr_arr[$modelid][$lot_no][$shopid]['units']= Unitmovement::where([['shop_id', '=', $shopid], ['current_shop', '=', 0 ], ['datetime_out', '=',$date]])->whereHas('vehicle',function ($query) use( $wq) {
                 $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


                 
                 $drr_arr[$modelid][$lot_no][$shopid]['defects'] = Querydefect::where([['shop_id', '=', $shopid], ['is_defect', '=', 'Yes']])->whereDate('created_at',$date)->whereHas('vehicle',function ($query) use( $wq) {
    $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();

               
               
            }
            
        }

     foreach($shops as $shoprow){
                $shop_id = $shoprow->id;
          $unit_count[$shop_id]['total_units'] = Unitmovement::where([['shop_id', '=', $shop_id], ['current_shop', '=', 0],['datetime_out', '=',$date]])->count();
          $shop_id = $shoprow->id;

            $unit_count[$shop_id]['total_defects'] = Querydefect::where([['shop_id', '=', $shop_id], ['is_defect', '=', 'Yes']])->whereDate('created_at',$date)->count();

            if($unit_count[$shop_id]['total_units']==0){
              $midscore=0;

           }else{
            $midscore=($unit_count[$shop_id]['total_defects'] / $unit_count[$shop_id]['total_units'])*100;
           }

            $unit_count[$shop_id]['mdiscore']=round($midscore,2);

             $unit_count[$shop_id]['targetscore']= DrrTargetShop::where([['shop_id', '=', $shop_id], ['target_id', '=', $target_details->id]])->value('target_value');

               
            }

  $plant_units = Unitmovement::where([['current_shop', '=', 0],['datetime_out', '=',$date]])->where('shop_id','8')->orWhere('shop_id','9')->orWhere('shop_id','13')->count();

$plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereDate('created_at',$date)->count();
$plant_target = $target_details->plant_target;
$pant_drl=0;
if($plant_units>0){
$pant_drl=round(($plant_defect/$plant_units),2);
}


      $data = array(
            'target'=>$target, 
            'heading'=>$heading, 
            'shops'=>$shops,
            'vehicles'=>$vehicles,
            'shopcount'=>$shopcount,
            'drr_arr'=>$drr_arr,
            'unit_count'=>$unit_count,
            'plant_units'=>$plant_units,
            'plant_defect'=>$plant_defect,
            'plant_target'=>$plant_target,
            'pant_drl'=>$pant_drl,
            'target_name'=>$target_name,
            'section'=>encrypt_data($section),
            'from'=>$from,
            'to'=>$to,
            'target_id'=>$target_id,
            'vehicle_models'=> $vehicle_models,
            
            
        );


       return view('productionreport.drl-month-todate')->with($data);
  
 }elseif ($section=='custom') {



          $target_details = DrrTarget::find(decrypt_data($target_id));
          $target_name = $target_details->target_name;

          $originalDate = decrypt_data($from);
          $start=date_for_database(decrypt_data($from));
          $end=date_for_database(decrypt_data($to));



                 
          $heading=' DIRECT RUN LOSS RESULTS FOR  '.date("D F Y", strtotime($start)).' TO '.date("D F Y", strtotime($end)).'';
          $startday=new Carbon($start);
          $endday = new Carbon($end);




          $starttwo=$startday->format("Y-m-d 00:00:00");
          $endtwo=$endday->format("Y-m-d H:i:s");


                  $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();

          //$shopnames = Shop::where('inspector','=','1')->distinct()->get('report_name');
          $shops = Shop::where('check_point','=','1')->get();
           $shopcount = Shop::where('check_point','=','1')->distinct()->count();

           $drr_arr = [];
        
           $unit_count = [];
            $i=1;
          
          
               foreach($vehicles as $vehicle){
            $modelid = $vehicle->model_id;
            $lot_no = $vehicle->lot_no;
            foreach($shops as $shop){
                $shopid = $shop->id;
                $wq = compact('modelid', 'lot_no');
               $drr_arr[$modelid][$lot_no][$shopid]['units'] = Unitmovement::where([['shop_id', '=', $shopid], ['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
                 $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


                  $drr_arr[$modelid][$lot_no][$shopid]['defects'] = Querydefect::where([['shop_id', '=', $shopid], ['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->whereHas('vehicle',function ($query) use( $wq) {
    $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


               
               
            }
            
        }

     foreach($shops as $shoprow){
                $shop_id = $shoprow->id;
          $unit_count[$shop_id]['total_units'] = Unitmovement::where([['shop_id', '=', $shop_id], ['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->count();
          $shop_id = $shoprow->id;

            $unit_count[$shop_id]['total_defects'] = Querydefect::where([['shop_id', '=', $shop_id], ['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->count();

            if($unit_count[$shop_id]['total_units']==0){
              $midscore=0;

           }else{
            $midscore=($unit_count[$shop_id]['total_defects'] / $unit_count[$shop_id]['total_units'])*100;
           }

            $unit_count[$shop_id]['mdiscore']=round($midscore,2);

             $unit_count[$shop_id]['targetscore']= DrrTargetShop::where([['shop_id', '=', $shop_id], ['target_id', '=', $target_details->id]])->value('target_value');

               
            }

  $plant_units = Unitmovement::where([['current_shop', '=', 0]])->where('shop_id','8')->orWhere('shop_id','9')->orWhere('shop_id','13')->whereBetween('datetime_out',[$start,$end])->count();

$plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->count();
$plant_target = $target_details->plant_target;
$pant_drl=0;
if($plant_units>0){
$pant_drl=round(($plant_defect/$plant_units),2);
}


      $data = array(
            'target'=>$target, 
            'heading'=>$heading, 
            'shops'=>$shops,
            'vehicles'=>$vehicles,
            'shopcount'=>$shopcount,
            'drr_arr'=>$drr_arr,
            'unit_count'=>$unit_count,
            'plant_units'=>$plant_units,
            'plant_defect'=>$plant_defect,
            'plant_target'=>$plant_target,
            'pant_drl'=>$pant_drl,
            'target_name'=>$target_name,
            'section'=>encrypt_data($section),
            'from'=>$from,
            'to'=>$to,
            'target_id'=>$target_id,
            'vehicle_models'=> $vehicle_models,
            
            
        );


       return view('productionreport.drl-month-todate')->with($data);




 }
        
    }


    //drr report

    public function drr($date,$section)
    {



      $date=decrypt_data($date);
      $section=decrypt_data($section);
    
//get units and group by lot and model number
      $current_shop=0;
      $vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                       $query->where('current_shop', $current_shop);
      })->get();
      $shops = Shop::where('check_point','=','1')->get();
    



          //Palnt

          

      if($section=='this_month'){

   $start=Carbon::createFromFormat('F Y', $date)->startOfMonth();;
$start=$start->format("Y-m-d");

$end=Carbon::createFromFormat('F Y', $date)->endOfMonth();
$end=$end->format("Y-m-d");
$target = DrrTarget::where('target_type','Drr')->pluck('target_name', 'id');

 $target_details = DrrTarget::where('active', 'Active')->where('target_type','Drr')->first();

$target_name = $target_details->target_name;
            


$heading='MONTH TO DATE DIRECT RUN RATE RESULTS FOR '.$date;

//CV


$cv_current_shop=0;
$cv_vehicles = vehicle_units::where(function ($q) {
  $q->where('route',1)->orWhere('route',2)->orWhere('route',2);
})->groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $cv_current_shop) {
                 $query->where('current_shop', $cv_current_shop);
})->get();



          $cv_shops = Shop::where('offline','=','1')->where('group_order','!=','0')->orderBy('group_order')->get();




           $cv_shopcount = Shop::where('offline','=','1')->where('group_order','!=','0')->distinct()->count();

           $cv_drr_arr = [];
        
           $cv_unit_count = [];
     

        
          $cv_vehicleid = [];
          $cv_totalunits=[];
          $cv_allunits=[];
               foreach($cv_vehicles as $cv_vehicle){
            $cv_modelid = $cv_vehicle->model_id;
            $cv_lot_no = $cv_vehicle->lot_no;
            foreach($cv_shops as $cv_shop){
                $cv_shopid = $cv_shop->id;

                $wq = compact('cv_modelid', 'cv_lot_no');
               
                $cv_datecheck='datetime_out';
                $cv_all_shop_id= $cv_shopid;

                if($cv_shopid ==28){
                  $cv_all_shop_id= 14;
                  $cv_datecheck= 'datetime_in';
                 

                }

                $cv_allunits=Unitmovement::where([['shop_id', '=', $cv_all_shop_id]])->whereBetween( $cv_datecheck,[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
                  $query->where('model_id', $wq['cv_modelid'])->where('lot_no', $wq['cv_lot_no']);
 })->count();

 

 $cv_mpa_pluck_units=Unitmovement::where([['shop_id', '=',  $cv_all_shop_id]])->whereBetween( $cv_datecheck,[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['cv_modelid'])->where('lot_no', $wq['cv_lot_no']);
})->pluck('vehicle_id')->all();


 $cv_ok_units = Drr::where([['shop_id', '=',  $cv_shopid]])->whereIn('vehicle_id',$cv_mpa_pluck_units)->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['cv_modelid'])->where('lot_no', $wq['cv_lot_no']);
})->distinct('vehicle_id')->count();




$cv_drr_arr[$cv_modelid][$cv_lot_no][$cv_shopid]['units']=$cv_allunits;

$cv_ok_units=$cv_allunits-$cv_ok_units;

$cv_drr_arr[$cv_modelid][$cv_lot_no][$cv_shopid]['drr']=$cv_ok_units;
$cv_drr_arr[$cv_modelid][$cv_lot_no][$cv_shopid]['score'] =0;

if($cv_allunits >0){
  $cv_drr_arr[$cv_modelid][$cv_lot_no][$cv_shopid]['score'] = round((($cv_ok_units/$cv_allunits)*100),2);

}
//

$cv_total_units= Unitmovement::whereHas('vehicle',function ($q)  {
  $q->where('route',1)->orWhere('route',2)->orWhere('route',2);
})->where([['shop_id', '=', $cv_all_shop_id]])->whereBetween( $cv_datecheck,[$start,$end])->count();

$cv_total_pluck_units=Unitmovement::whereHas('vehicle',function ($q)  {
  $q->where('route',1)->orWhere('route',2)->orWhere('route',2);
})->where([['shop_id', '=',  $cv_all_shop_id]])->whereBetween( $cv_datecheck,[$start,$end])->pluck('vehicle_id')->all();


$cv_total_ok_units= Drr::where([['shop_id', '=',  $cv_shopid]])->whereIn('vehicle_id',$cv_total_pluck_units)->distinct('vehicle_id')->count();

$cv_total_ok_units= $cv_total_units-$cv_total_ok_units;

$cv_totalunits[$cv_shopid]['total_units']=$cv_total_units;
$cv_totalunits[$cv_shopid]['total_ok_units']=$cv_total_ok_units;
$cv_totalunits[$cv_shopid]['total_score']=0;
if($cv_total_units >0){
  $cv_totalunits[$cv_shopid]['total_score'] = round((($cv_total_ok_units/$cv_total_units)*100),2);

}

      
               
           }
            
        }



//LCV



//Plant


           $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();



          $shops = Shop::where('offline','=','1')->where('group_order','!=','0')->orderBy('group_order')->get();




           $shopcount = Shop::where('offline','=','1')->where('group_order','!=','0')->distinct()->count();

           $drr_arr = [];
        
           $unit_count = [];
     

        
          $vehicleid = [];
          $totalunits=[];
          $allunits=[];
               foreach($vehicles as $vehicle){
            $modelid = $vehicle->model_id;
            $lot_no = $vehicle->lot_no;
            foreach($shops as $shop){
                $shopid = $shop->id;

                $wq = compact('modelid', 'lot_no');
               
                $datecheck='datetime_out';
                $all_shop_id= $shopid;

                if($shopid ==28){
                  $all_shop_id= 14;
                  $datecheck= 'datetime_in';
                 

                }

                $allunits=Unitmovement::where([['shop_id', '=', $all_shop_id]])->whereBetween( $datecheck,[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
                  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
 })->count();

 

 $mpa_pluck_units=Unitmovement::where([['shop_id', '=',  $all_shop_id]])->whereBetween( $datecheck,[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->pluck('vehicle_id')->all();


 $ok_units = Drr::where([['shop_id', '=',  $shopid]])->whereIn('vehicle_id',$mpa_pluck_units)->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->distinct('vehicle_id')->count();


$drr_arr[$modelid][$lot_no][$shopid]['units']=$allunits;

$ok_units=$allunits-$ok_units;

$drr_arr[$modelid][$lot_no][$shopid]['drr']=$ok_units;
$drr_arr[$modelid][$lot_no][$shopid]['score'] =0;

if($allunits >0){
  $drr_arr[$modelid][$lot_no][$shopid]['score'] = round((($ok_units/$allunits)*100),2);

}

$total_units= Unitmovement::where([['shop_id', '=', $all_shop_id]])->whereBetween( $datecheck,[$start,$end])->count();

$total_pluck_units=Unitmovement::where([['shop_id', '=',  $all_shop_id]])->whereBetween( $datecheck,[$start,$end])->pluck('vehicle_id')->all();


$total_ok_units= Drr::where([['shop_id', '=',  $shopid]])->whereIn('vehicle_id',$total_pluck_units)->distinct('vehicle_id')->count();

$total_ok_units= $total_units-$total_ok_units;

$totalunits[$shopid]['total_units']=$total_units;
$totalunits[$shopid]['total_ok_units']=$total_ok_units;
$totalunits[$shopid]['total_score']=0;
if($total_units >0){
  $totalunits[$shopid]['total_score'] = round((($total_ok_units/$total_units)*100),2);

}

      
               
           }
            
        }
       
   
        
 }elseif ($section=='this_year') {

  $start=Carbon::createFromFormat('Y', $date)->startOfYear();;
$start=$start->format("Y-m-d");

$end=Carbon::createFromFormat('Y', $date)->endOfYear();
$end=$end->format("Y-m-d");

$target = DrrTarget::where('target_type','Drr')->pluck('target_name', 'id');
$target_details = DrrTarget::where('active', 'Active')->where('target_type','Drr')->first();

$target_name = $target_details->target_name;
            


$heading='MONTH TO DATE DIRECT RUN RATE RESULTS FOR '.$date;


           $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();



          $shops = Shop::where('offline','=','1')->where('group_order','!=','0')->orderBy('group_order')->get();




           $shopcount = Shop::where('offline','=','1')->where('group_order','!=','0')->distinct()->count();

           $drr_arr = [];
        
           $unit_count = [];
          //  $i=1;
          //  $start='2021-10-01';
           // $stendart='2021-12-24';

        
          $vehicleid = [];
          $totalunits=[];
          $allunits=[];
               foreach($vehicles as $vehicle){
            $modelid = $vehicle->model_id;
            $lot_no = $vehicle->lot_no;
            foreach($shops as $shop){
                $shopid = $shop->id;

                $wq = compact('modelid', 'lot_no');
               
                $datecheck='datetime_out';
                $all_shop_id= $shopid;
 //all vehicles going to DTC 
                if($shopid ==28){
                  $all_shop_id= 14;
                  $datecheck= 'datetime_in';
                 

                }

                $allunits=Unitmovement::where([['shop_id', '=', $all_shop_id]])->whereBetween( $datecheck,[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
                  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
 })->count();

 

 $mpa_pluck_units=Unitmovement::where([['shop_id', '=',  $all_shop_id]])->whereBetween( $datecheck,[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->pluck('vehicle_id')->all();


 $ok_units = Drr::where([['shop_id', '=',  $shopid]])->whereIn('vehicle_id',$mpa_pluck_units)->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->distinct('vehicle_id')->count();


$drr_arr[$modelid][$lot_no][$shopid]['units']=$allunits;

$ok_units=$allunits-$ok_units;

$drr_arr[$modelid][$lot_no][$shopid]['drr']=$ok_units;
$drr_arr[$modelid][$lot_no][$shopid]['score'] =0;

if($allunits >0){
  $drr_arr[$modelid][$lot_no][$shopid]['score'] = round((($ok_units/$allunits)*100),2);

}

$total_units= Unitmovement::where([['shop_id', '=', $all_shop_id]])->whereBetween( $datecheck,[$start,$end])->count();

$total_pluck_units=Unitmovement::where([['shop_id', '=',  $all_shop_id]])->whereBetween( $datecheck,[$start,$end])->pluck('vehicle_id')->all();


$total_ok_units= Drr::where([['shop_id', '=',  $shopid]])->whereIn('vehicle_id',$total_pluck_units)->distinct('vehicle_id')->count();

$total_ok_units= $total_units-$total_ok_units;

$totalunits[$shopid]['total_units']=$total_units;
$totalunits[$shopid]['total_ok_units']=$total_ok_units;
$totalunits[$shopid]['total_score']=0;
if($total_units >0){
  $totalunits[$shopid]['total_score'] = round((($total_ok_units/$total_units)*100),2);

}

      
               
           }
            
        }






 


  
 }elseif ($section=='today') {

  $start=date_for_database($date);

$end=date_for_database($date);

$date_dispaly=new Carbon($start);

$date_dispaly=$date_dispaly->format("D F Y");



$target = DrrTarget::where('target_type','Drr')->pluck('target_name', 'id');
$target_details = DrrTarget::where('active', 'Active')->where('target_type','Drr')->first();

$target_name = $target_details->target_name;
            

$heading=' DIRECT RUN RATE RESULTS FOR  '.$date;


$heading='MONTH TO DATE DIRECT RUN RATE RESULTS FOR '.$date_dispaly;


           $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();



          $shops = Shop::where('offline','=','1')->where('group_order','!=','0')->orderBy('group_order')->get();




           $shopcount = Shop::where('offline','=','1')->where('group_order','!=','0')->distinct()->count();

           $drr_arr = [];
        
           $unit_count = [];
          //  $i=1;
          //  $start='2021-10-01';
           // $stendart='2021-12-24';

        
          $vehicleid = [];
          $totalunits=[];
          $allunits=[];
               foreach($vehicles as $vehicle){
            $modelid = $vehicle->model_id;
            $lot_no = $vehicle->lot_no;
            foreach($shops as $shop){
                $shopid = $shop->id;

                $wq = compact('modelid', 'lot_no');
               
                $datecheck='datetime_out';
                $all_shop_id= $shopid;
 //all vehicles going to DTC 
                if($shopid ==28){
                  $all_shop_id= 14;
                  $datecheck= 'datetime_in';
                 

                }

                $allunits=Unitmovement::where([['shop_id', '=', $all_shop_id]])->whereDate( $datecheck,$start)->whereHas('vehicle',function ($query) use( $wq) {
                  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
 })->count();

 

 $mpa_pluck_units=Unitmovement::where([['shop_id', '=',  $all_shop_id]])->whereDate( $datecheck,$start)->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->pluck('vehicle_id')->all();


 $ok_units = Drr::where([['shop_id', '=',  $shopid]])->whereIn('vehicle_id',$mpa_pluck_units)->whereHas('vehicle',function ($query) use( $wq) {
  $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->distinct('vehicle_id')->count();


$drr_arr[$modelid][$lot_no][$shopid]['units']=$allunits;

$ok_units=$allunits-$ok_units;

$drr_arr[$modelid][$lot_no][$shopid]['drr']=$ok_units;
$drr_arr[$modelid][$lot_no][$shopid]['score'] =0;

if($allunits >0){
  $drr_arr[$modelid][$lot_no][$shopid]['score'] = round((($ok_units/$allunits)*100),2);

}

$total_units= Unitmovement::where([['shop_id', '=', $all_shop_id]])->whereDate( $datecheck,$start)->count();

$total_pluck_units=Unitmovement::where([['shop_id', '=',  $all_shop_id]])->whereDate( $datecheck,$start)->pluck('vehicle_id')->all();


$total_ok_units= Drr::where([['shop_id', '=',  $shopid]])->whereIn('vehicle_id',$total_pluck_units)->distinct('vehicle_id')->distinct('vehicle_id')->count();

$total_ok_units= $total_units-$total_ok_units;

$totalunits[$shopid]['total_units']=$total_units;
$totalunits[$shopid]['total_ok_units']=$total_ok_units;
$totalunits[$shopid]['total_score']=0;
if($total_units >0){
  $totalunits[$shopid]['total_score'] = round((($total_ok_units/$total_units)*100),2);

}

      
               
           }
            
        }






 }





 $data = array(



  'cv_shops'=>$cv_shops,
  'cv_vehicles'=>$cv_vehicles,
  'cv_shopcount'=>$cv_shopcount,
  'cv_drr_arr'=>$cv_drr_arr,
  'cv_totalunits'=>$cv_totalunits,

  //pant
  'target'=>$target, 
  'heading'=>$heading, 
  'shops'=>$shops,
  'vehicles'=>$vehicles,
  'shopcount'=>$shopcount,
  'drr_arr'=>$drr_arr,
  'totalunits'=>$totalunits,
  'target_name'=>$target_name,
  'section'=>$section,
  'date'=>$date,

  
  
);


 return view('productionreport.drr-month-todate')->with($data);
        
    }

    public function filtertoday(Request $request)
    {

     // dd(date_for_database($request->from_date_single));

      

      if($request->select_id==1){
      $date=encrypt_data($request->from_date_single);
      $target_id=encrypt_data($request->target_id);
      $section=encrypt_data('daily');

      return redirect()->route('drl', ['section' =>$section ,'from'=>$date,'to'=>'to','target_id'=>$target_id]);
    }elseif ($request->select_id==2) {

      $fromdate=encrypt_data($request->from_custom_date_single);
      $todate=encrypt_data($request->to_custom_date_single);
      $target_id=encrypt_data($request->custom_target_id);
      $section=encrypt_data('custom');

      return redirect()->route('drl', ['section' => $section,'from'=>$fromdate,'to'=>$todate,'target_id'=>$target_id]);
     
    }

  }
 public function drrfiltertoday(Request $request)
    {


     
    
     
      $date=encrypt_data($request->month_date);
      $record=encrypt_data($request->section);
      return redirect()->route('drr', ['date'=>$date, 'section'=>$record]);

  }



   public function exportdrl($section,$from,$to,$target_id) 
    {
      $data = array();
      $data['section'] = $section;
      $data['from'] = $from;
      $data['to'] = $to;
      $data['target_id'] = $target_id;

        return Excel::download(new DrlExport($data), ''.decrypt_data($section).'_DRL_Report.xlsx');
    } 

    public function exportdrr($section,$from,$to,$target_id) 
    {
      $data = array();
      $data['section'] = $section;
      $data['from'] = $from;
      $data['to'] = $to;
      $data['target_id'] = $target_id;

      //dd($data);
        return Excel::download(new DrrExport($data), ''.decrypt_data($section).'_DRR_Report.xlsx');
    } 

 public function defectsummary($from,$to) 
    {

      

$from=decrypt_data($from);
$to=decrypt_data($to);

      if($from!='null' && $to!='null' ){
  

         $originalDate = $from;
          $start=date_for_database($from);
          $end=date_for_database($to);


    $start_date = date('Y-m-d 00:00:00', strtotime($start));

$end_date = date('Y-m-d 23:59:59', strtotime($end));     




    


         $heading='Month to Date  Defect Summary for '.date("D F Y", strtotime($start)).' TO '.date("D F Y", strtotime($end)).'';

        $defects = Querydefect::with(['getqueryanswer.doneby','getqueryanswer.routing.category'])->whereBetween('created_at',[$start_date,$end_date])->get();






      }else{

 $heading='Month to Date  Defect Summary for '.date('F Y');
 $startDate = Carbon::now(); //returns current day
    $endDate = Carbon::now(); //returns current day
    $firstDay = $startDate->firstOfMonth();
   $endDay = $endDate->endOfMonth();
    $start=$firstDay->format("Y-m-d");
    $end=$endDay->format("Y-m-d");

    //target
    $today=Carbon::now();
    $today=$today->format("Y-m-d");

    $start_date = date('Y-m-d 00:00:00', strtotime($start));

     $end_date = date('Y-m-d 23:59:59', strtotime($end));




       
        $defects = Querydefect::with(['getqueryanswer.doneby','getqueryanswer.routing.category'])->whereBetween('created_at',[$start_date,$end_date])->get();






      }


      

$shops = Shop::where('check_point','=','1')->get();
$shopdata=[];
        foreach( $shops as  $shop){
  $shopdata[]=array(
    'value'=> $shop->id,
    'text'=> $shop->shop_name,

  );

}


$floatsettings = FloatSetting::get();
$defectcategory=[];

      foreach( $floatsettings as  $floatsetting){
  $defectcategory[]=array(
    'value'=> $floatsetting->float_name,
    'text'=> $floatsetting->float_name,

  );

}


             $data = array(
            'heading'=>$heading,
            'defects'=>$defects, 
            'shops'=>json_encode($shopdata), 
            'defectcategory'=>json_encode($defectcategory), 
           
        );


      return view('defects.index')->with($data);;

    }



public function drrlist($date,$record) 
    {

      

$date=decrypt_data($date);
$record=decrypt_data($record);

if($record=='this_month' ){

$start=Carbon::createFromFormat('F Y', $date)->startOfMonth();
$start=$start->format("Y-m-d");

$end=Carbon::createFromFormat('F Y', $date)->endOfMonth();
$end=$end->format("Y-m-d");

$heading='Month to Date  DRR List For '.$date;

}else if($record=='this_year'){

$start=Carbon::createFromFormat('Y', $date)->startOfYear();;
$start=$start->format("Y-m-d");

$end=Carbon::createFromFormat('Y', $date)->endOfYear();
$end=$end->format("Y-m-d");

$heading='Year to Date  DRR List For '.$date; 


}else if($record=='today'){


$start=date_for_database($date);

$end=date_for_database($date);

$date_dispaly=new Carbon($start);

$date_dispaly=$date_dispaly->format("D F Y");




$heading='Year to Date  DRR List For '.$date_dispaly; 



}

$defects = Querydefect::with(['getqueryanswer.doneby','getqueryanswer.routing.category'])->whereBetween('created_at',[$start,$end])->get();

if($record=='today'){

$defects = Querydefect::with(['getqueryanswer.doneby','getqueryanswer.routing.category'])->whereBetween('created_at',[$start,$end])->get();
}





     /*  
      if (request()->ajax()) {

        

        $defects = Drr::with(['vehicle.model','shop','doneby'])->whereBetween('created_at',[$start,$end])->get();
        if($record=='today'){
          $defects = Drr::with(['vehicle.model','shop','doneby'])->whereDate('created_at',$start)->get();
        }

    return DataTables::of($defects)

       ->addColumn('action', function ($defects) {
            return '
                 <a href="' . route('drrdata.destroy', [encrypt_data($defects->id)]) . '" class="btn btn-xs btn-danger delete_brand_button delete-defect"><i class="glyphicon glyphicon-trash"></i> Delete</a>
             ';
        })
       ->addColumn('created_at', function ($defects) {
            return dateFormat($defects->created_at);
        })

   ->addColumn('vin_no', function ($defects) {
            return $defects->vehicle->vin_no;
        })
   ->addColumn('lot_no', function ($defects) {
            return $defects->vehicle->lot_no;
        })
   ->addColumn('vin_no', function ($defects) {
            return $defects->vehicle->vin_no;
        })
   ->addColumn('job_no', function ($defects) {
            return $defects->vehicle->job_no;
        })
    ->addColumn('shop_name', function ($defects) {
            return $defects->shop->shop_name;
        })
     ->addColumn('doneby', function ($defects) {
            return $defects->doneby->name;
        })




 ->make(true);
   

}
*/

      

$shops = Shop::where('check_point','=','1')->get();
$shopdata=[];
        foreach( $shops as  $shop){
  $shopdata[]=array(
    'value'=> $shop->id,
    'text'=> $shop->shop_name,

  );

}


$floatsettings = FloatSetting::get();
$defectcategory=[];

      foreach( $floatsettings as  $floatsetting){
  $defectcategory[]=array(
    'value'=> $floatsetting->float_name,
    'text'=> $floatsetting->float_name,

  );

}

//$thismonth=Carbon::now();
//$thismonth=$today->format("F Y");
//$thisyear=$today->format("Y");





             $data = array(
            'heading'=>$heading,
            'record'=>$record,
            'date'=>$date, 
            'defects'=>$defects, 
            'shops'=>json_encode($shopdata), 
            'defectcategory'=>json_encode($defectcategory), 
           
           
        );


      return view('drrlist.index')->with($data);;

    }

   

     public function updatedefect(Request $request, $id)
    {
      if(decrypt_data($id)=='gca'){
      if($request->ajax()){
          Querydefect::find($request->input('pk'))->update([$request->input('name') => $request->input('value')]);
          return response()->json(['success' => true,'data'=>$request->input('name')]);
      }

    }else if(decrypt_data($id)=='shop'){

      if($request->ajax()){
          Queryanswer::find($request->input('pk'))->update([$request->input('name') => $request->input('value')]);
          return response()->json(['success' => true,'data'=>$request->input('name')]);
      }


    }elseif(decrypt_data($id)=='defect_category'){

         if($request->ajax()){
          Querydefect::find($request->input('pk'))->update([$request->input('name') => $request->input('value')]);
          return response()->json(['success' => true,'data'=>$request->input('name')]);
      }





    }elseif(decrypt_data($id)=='stakeholder'){

         if($request->ajax()){
          Querydefect::find($request->input('pk'))->update([$request->input('name') => $request->input('value')]);
          return response()->json(['success' => true,'data'=>$request->input('name')]);
      }





    }




    }

    
    

     public function filterdefect(Request $request)
    {

     // filter difect
      $fromdate=encrypt_data($request->from_custom_date_single);
      $todate=encrypt_data($request->to_custom_date_single);
      return redirect()->route('defectsummary', ['from'=>$fromdate,'to'=>$todate]);
     
  

  }

   public function filterdrrdefect(Request $request)
    {

     // filter monthly difect
     
      $date=encrypt_data($request->month_date);
      $record=encrypt_data($request->record);
      return redirect()->route('drrlist', ['from'=>$date,'to'=>$record]);
     
  

  }



  public function updatedrr(Request $request, $id)
  {
    
    if($request->ajax()){
      $updatedata= Querydefect::find($request->input('pk'))->update([$request->input('name') => $request->input('value')]);
       




            //book drr and if not booked
            
          
if($updatedata){

  $units_details=Querydefect::find($request->input('pk'));

  
            

            if($request->input('name')=='mpb_drr'){
              $shop_id=15;
              $count_defects=Querydefect::where('vehicle_id',$units_details->vehicle_id)->where('mpb_drr','1')->count();

            }else if($request->input('name')=='mpc_drr'){
              $shop_id=16;
              $count_defects=Querydefect::where('vehicle_id',$units_details->vehicle_id)->where('mpc_drr','1')->count();

            }else{

              $shop_id=28;
              $count_defects=Querydefect::where('vehicle_id',$units_details->vehicle_id)->where('mpa_drr','1')->count();


            }
          


            $drrrecord=array();

            if( $count_defects>0){
              $drr_exist=$this->check_drr($units_details->vehicle_id,$shop_id);

              if(!$drr_exist){
                          $drrrecord['vehicle_id']=$units_details->vehicle_id;
                          $drrrecord['shop_id']=$shop_id;
                          $drrrecord['is_app_or_system']=1;
                          $drrrecord['done_by']=auth()->user()->id;
                          $drrsave = Drr::create($drrrecord);
       
              }else{
              //existing but status changed

              $v_update = Drr::where('vehicle_id',$units_details->vehicle_id); 
              $v_update->update(['use_drr' => '1']);


              }

       

             




            }else{


            $v_update = Drr::where('vehicle_id',$units_details->vehicle_id); 
            $v_update->update(['use_drr' => '0']);
        




            }
  
}
          
            

            return response()->json(['success' => true,'data'=>$request->input('name')]);
     
    }

 




  }

  public function check_drr($value,$value1)
    {
        return  Drr::where('vehicle_id', $value)->where('shop_id',$value1)->exists();
    }

  

   

  

}
