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
use Carbon\Carbon;

class DrlExport implements FromView
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

           $current_shop=0;
$vehicles = vehicle_units::groupBy('lot_no','model_id')->whereHas('unitmovement',function ($query) use( $current_shop) {
                 $query->where('current_shop', $current_shop);
})->get();



          $shops = Shop::where('check_point','=','1')->get();




           $shopcount = Shop::where('check_point','=','1')->distinct()->count();

           $drr_arr = [];
        
           $unit_count = [];
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

  $plant_units = Unitmovement::where([['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->count();

$plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->count();
$plant_target = $target_details->plant_target;
$pant_drl=0;
if($plant_units>0){
$pant_drl=round(($plant_defect/$plant_units),2);
}


      $data = array(
            
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
           
          
            
            
        );


       return view('export.drl')->with($data);
        
 }elseif ($section=='daily') {

$target_details = DrrTarget::find($target_id);
  $target_name = $target_details->target_name;

$originalDate = $from;
$date=date_for_database($from);





          $originalDate = $from;
          $heading = $heading='DAILY  DIRECT RUN LOSS RESULTS FOR '. date("d F Y", strtotime($originalDate)) ;
          $date=date_for_database($from);

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

  $plant_units = Unitmovement::where([['current_shop', '=', 0],['datetime_out', '=',$date]])->count();

$plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereDate('created_at',$date)->count();
$plant_target = $target_details->plant_target;
$pant_drl=0;
if($plant_units>0){
$pant_drl=round(($plant_defect/$plant_units),2);
}


      $data = array(
           
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
          
            
        );



        return view('export.drl')->with($data);
  
 }elseif ($section=='custom') {



          
          $target_details = DrrTarget::find($target_id);
          $target_name = $target_details->target_name;

          $originalDate = $from;
          $start=date_for_database($from);
          $end=date_for_database($to);



                 
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

  $plant_units = Unitmovement::where([['current_shop', '=', 0]])->whereBetween('datetime_out',[$start,$end])->count();

$plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->count();
$plant_target = $target_details->plant_target;
$pant_drl=0;
if($plant_units>0){
$pant_drl=round(($plant_defect/$plant_units),2);
}


      $data = array(

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
          
            
            
        );


        return view('export.drl')->with($data);



}

 }


}
