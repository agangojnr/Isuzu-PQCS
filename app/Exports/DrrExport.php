<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\unitmovement\Unitmovement;
use App\Models\shop\Shop;
use App\Models\unit_model\Unit_model;
use Illuminate\Support\Facades\DB;
use App\Models\queryanswer\Queryanswer;
use App\Models\querydefect\Querydefect;
use App\Models\drrtarget\DrrTarget;
use App\Models\vehicle_units\vehicle_units;
use App\Models\drrtargetshop\DrrTargetShop;

use App\Models\drr\Drr;
use Carbon\Carbon;

class DrrExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
  
  private $data;

 public function __construct(array $data = [])
    {
        $this->data = $data;
    }
      public function view(): View
    {


$section=decrypt_data($this->data['section']);
$from=decrypt_data($this->data['from']);
$to=decrypt_data($this->data['to']);
$target_id=decrypt_data($this->data['target_id']);


      $shops = Shop::where('check_point','=','1')->get();

      if($section=='month_to_date'){

        $target_details = DrrTarget::where('active', 'Active')->where('target_type','Drr')->first();

$target_name = $target_details->target_name;
            


$heading='MONTH TO DATE DIRECT RUN RATE RESULTS FOR '.date('F Y');
$today=Carbon::now();
$startDate = Carbon::now(); //returns current day
$firstDay = $startDate->firstOfMonth(); 
$end=$today->format("Y-m-d");
$start=$firstDay->format("Y-m-d");


$endtwo=$today->format("Y-m-d H:i:s");

$starttwo=$firstDay->format("Y-m-d 00:00:00");




           $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();



          $shops = Shop::where('offline','=','1')->get();




           $shopcount = Shop::where('offline','=','1')->distinct()->count();

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
                $drr_arr[$modelid][$lot_no][$shopid]['units'] = Unitmovement::where([['shop_id', '=', $shopid]])->whereBetween('datetime_in',[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
                 $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


                 $drr_arr[$modelid][$lot_no][$shopid]['defects'] = Drr::where([['shop_id', '=', $shopid]])->whereBetween('created_at',[$starttwo,$endtwo])->whereHas('vehicle',function ($query) use( $wq) {
    $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();



 $drr_arr[$modelid][$lot_no][$shopid]['drr'] = $drr_arr[$modelid][$lot_no][$shopid]['units']-$drr_arr[$modelid][$lot_no][$shopid]['defects'];

$drr_arr[$modelid][$lot_no][$shopid]['score'] =0;
 if($drr_arr[$modelid][$lot_no][$shopid]['units']>0){
   $drr_arr[$modelid][$lot_no][$shopid]['score'] = round((($drr_arr[$modelid][$lot_no][$shopid]['drr']/$drr_arr[$modelid][$lot_no][$shopid]['units'])*100),2);

 }

               
               
            }
            
        }



$pant_drr=[];
$pant_drr_pc=[];
     foreach($shops as $shoprow){
                $shop_id = $shoprow->id;
          $unit_count[$shop_id]['total_units'] = Unitmovement::where([['shop_id', '=', $shop_id]])->whereBetween('datetime_in',[$start,$end])->count();
          $shop_id = $shoprow->id;

            $unit_count[$shop_id]['total_defects'] = Drr::where([['shop_id', '=', $shop_id]])->whereBetween('created_at',[$starttwo,$endtwo])->count();

$unit_count[$shop_id]['total_drr']=$unit_count[$shop_id]['total_units']-$unit_count[$shop_id]['total_defects'];


       $midscore=0;
            if($unit_count[$shop_id]['total_units']>0){
            
        $midscore=($unit_count[$shop_id]['total_drr'] / $unit_count[$shop_id]['total_units'])*100;
           }

        $unit_count[$shop_id]['mdiscore']=round($midscore,2);

        $pant_drr[] =$midscore;
        $pant_drr_pc[]=100;

        $unit_count[$shop_id]['targetscore']= DrrTargetShop::where([['shop_id', '=', $shop_id], ['target_id', '=', $target_details->id]])->value('target_value');

               
            } 

$plant_target = $target_details->plant_target;
$tt=(array_product($pant_drr)/array_product($pant_drr_pc))*100; 
$pant_drr=round($tt,2);  


      $data = array(
            'heading'=>$heading, 
            'shops'=>$shops,
            'vehicles'=>$vehicles,
            'shopcount'=>$shopcount,
            'drr_arr'=>$drr_arr,
            'unit_count'=>$unit_count,
            'plant_target'=>$plant_target,
            'pant_drr'=>$pant_drr,
            'target_name'=>$target_name,
           
          
            
            
        );

  return view('export.drr')->with($data);


      
        
 }elseif ($section=='daily') {


  $target_details = DrrTarget::find($target_id);
  $target_name = $target_details->target_name;

$originalDate = $from;
$date=date_for_database($from);





          $originalDate = $from;
          $heading = $heading='DAILY  DIRECT RUN RATE RESULTS FOR '. date("d F Y", strtotime($originalDate)) ;
          $date=date_for_database($from);

           //$models = Unit_Model::All();
          //$shopnames = Shop::where('inspector','=','1')->distinct()->get('report_name');


                 $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();





         $shops = Shop::where('offline','=','1')->get();
          $shopcount = Shop::where('offline','=','1')->distinct()->count();

           $drr_arr = [];
           $unit_count = [];
            $i=1;
        
          
               foreach($vehicles as $vehicle){
            $modelid = $vehicle->model_id;
            $lot_no = $vehicle->lot_no;
            foreach($shops as $shop){
                $shopid = $shop->id;
                $wq = compact('modelid', 'lot_no');
                  $drr_arr[$modelid][$lot_no][$shopid]['units']= Unitmovement::where([['shop_id', '=', $shopid], ['datetime_in', '=',$date]])->whereHas('vehicle',function ($query) use( $wq) {
                 $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


                 
                 $drr_arr[$modelid][$lot_no][$shopid]['defects']  = Drr::where([['shop_id', '=', $shopid]])->whereDate('created_at',$date)->whereHas('vehicle',function ($query) use( $wq) {
    $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


                  $drr_arr[$modelid][$lot_no][$shopid]['drr'] = $drr_arr[$modelid][$lot_no][$shopid]['units']-$drr_arr[$modelid][$lot_no][$shopid]['defects'];

$drr_arr[$modelid][$lot_no][$shopid]['score'] =0;
 if($drr_arr[$modelid][$lot_no][$shopid]['units']>0){
   $drr_arr[$modelid][$lot_no][$shopid]['score'] = round((($drr_arr[$modelid][$lot_no][$shopid]['drr']/$drr_arr[$modelid][$lot_no][$shopid]['units'])*100),2);

 }

               
               
            }
            
        }

     foreach($shops as $shoprow){
                $shop_id = $shoprow->id;
          $unit_count[$shop_id]['total_units'] = Unitmovement::where([['shop_id', '=', $shop_id],['datetime_in', '=',$date]])->count();
          $shop_id = $shoprow->id;

            $unit_count[$shop_id]['total_defects'] = Drr::where([['shop_id', '=', $shop_id]])->whereDate('created_at',$date)->count();

            $unit_count[$shop_id]['total_drr']=$unit_count[$shop_id]['total_units']-$unit_count[$shop_id]['total_defects'];


       $midscore=0;
            if($unit_count[$shop_id]['total_units']>0){
            
        $midscore=($unit_count[$shop_id]['total_drr'] / $unit_count[$shop_id]['total_units'])*100;
           }

        $unit_count[$shop_id]['mdiscore']=round($midscore,2);

        $pant_drr[] =$midscore;
        $pant_drr_pc[]=100;

        $unit_count[$shop_id]['targetscore']= DrrTargetShop::where([['shop_id', '=', $shop_id], ['target_id', '=', $target_details->id]])->value('target_value');

               
            } 

$plant_target = $target_details->plant_target;
$tt=(array_product($pant_drr)/array_product($pant_drr_pc))*100; 
$pant_drr=round($tt,2); 


      $data = array(
         
            'heading'=>$heading, 
            'shops'=>$shops,
            'vehicles'=>$vehicles,
            'shopcount'=>$shopcount,
            'drr_arr'=>$drr_arr,
            'unit_count'=>$unit_count,
            'plant_target'=>$plant_target,
            'pant_drr'=>$pant_drr,
            'target_name'=>$target_name,
          
            
            
        );


        return view('export.drr')->with($data);
  
 }elseif ($section=='custom') {



          $target_details = DrrTarget::find($target_id);
          $target_name = $target_details->target_name;

          $originalDate = $from;
          $start=date_for_database($from);
          $end=date_for_database($to);



                 
          $heading=' DIRECT RUN RATE RESULTS FOR  '.date("D F Y", strtotime($start)).' TO '.date("D F Y", strtotime($end)).'';
          $startday=new Carbon($start);
          $endday = new Carbon($end);




          $starttwo=$startday->format("Y-m-d 00:00:00");
          $endtwo=$endday->format("Y-m-d H:i:s");


                  $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();

          //$shopnames = Shop::where('inspector','=','1')->distinct()->get('report_name');
          $shops = Shop::where('offline','=','1')->get();
         $shopcount = Shop::where('offline','=','1')->distinct()->count();

           $drr_arr = [];
        
           $unit_count = [];
            $i=1;
          
          
               foreach($vehicles as $vehicle){
            $modelid = $vehicle->model_id;
            $lot_no = $vehicle->lot_no;
            foreach($shops as $shop){
                $shopid = $shop->id;
                $wq = compact('modelid', 'lot_no');
               $drr_arr[$modelid][$lot_no][$shopid]['units'] = Unitmovement::where([['shop_id', '=', $shopid]])->whereBetween('datetime_in',[$start,$end])->whereHas('vehicle',function ($query) use( $wq) {
                 $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


                  $drr_arr[$modelid][$lot_no][$shopid]['defects'] = Drr::where([['shop_id', '=', $shopid]])->whereBetween('created_at',[$starttwo,$endtwo])->whereHas('vehicle',function ($query) use( $wq) {
    $query->where('model_id', $wq['modelid'])->where('lot_no', $wq['lot_no']);
})->count();


                $drr_arr[$modelid][$lot_no][$shopid]['drr'] = $drr_arr[$modelid][$lot_no][$shopid]['units']-$drr_arr[$modelid][$lot_no][$shopid]['defects'];

$drr_arr[$modelid][$lot_no][$shopid]['score'] =0;
 if($drr_arr[$modelid][$lot_no][$shopid]['units']>0){
   $drr_arr[$modelid][$lot_no][$shopid]['score'] = round((($drr_arr[$modelid][$lot_no][$shopid]['drr']/$drr_arr[$modelid][$lot_no][$shopid]['units'])*100),2);

 }
               
            }
            
        }

     foreach($shops as $shoprow){
                $shop_id = $shoprow->id;
          $unit_count[$shop_id]['total_units'] = Unitmovement::where([['shop_id', '=', $shop_id]])->whereBetween('datetime_in',[$start,$end])->count();
          $shop_id = $shoprow->id;

            $unit_count[$shop_id]['total_defects'] = Drr::where([['shop_id', '=', $shop_id]])->whereBetween('created_at',[$starttwo,$endtwo])->count();

           $unit_count[$shop_id]['total_drr']=$unit_count[$shop_id]['total_units']-$unit_count[$shop_id]['total_defects'];


       $midscore=0;
            if($unit_count[$shop_id]['total_units']>0){
            
        $midscore=($unit_count[$shop_id]['total_drr'] / $unit_count[$shop_id]['total_units'])*100;
           }

        $unit_count[$shop_id]['mdiscore']=round($midscore,2);

        $pant_drr[] =$midscore;
        $pant_drr_pc[]=100;

        $unit_count[$shop_id]['targetscore']= DrrTargetShop::where([['shop_id', '=', $shop_id], ['target_id', '=', $target_details->id]])->value('target_value');

               
            } 

$plant_target = $target_details->plant_target;
$tt=(array_product($pant_drr)/array_product($pant_drr_pc))*100; 
$pant_drr=round($tt,2); 

      $data = array(
            
            'heading'=>$heading, 
            'shops'=>$shops,
            'vehicles'=>$vehicles,
            'shopcount'=>$shopcount,
            'drr_arr'=>$drr_arr,
            'unit_count'=>$unit_count,
            'plant_target'=>$plant_target,
            'pant_drr'=>$pant_drr,
            'target_name'=>$target_name,
          
            
            
        );


    return view('export.drr')->with($data);




 }

 }


}
