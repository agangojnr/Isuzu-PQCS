<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\querydefect\Querydefect;
use App\Models\drrtarget\DrrTarget;
use App\Models\unitmovement\Unitmovement;
use App\Models\vehicle_units\vehicle_units;
use App\Models\shop\Shop;
use App\Models\drr\Drr;
use App\Models\drrtargetshop\DrrTargetShop;
use App\Models\attendance\Attendance;
use App\Models\employee\Employee;
use App\Models\indivtarget\IndivTarget;
use App\Models\productiontarget\Production_target;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use App\Models\gcascore\GcaScore;
use App\Models\gcatarget\GcaTarget;
use App\Models\std_working_hr\Std_working_hr;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


       /*$vehis = Unitmovement::where([['shop_id ',11],['std_hrs',null],['current_shop',0]])->get(['vehicle_id']);
foreach($vehis as $veh){
    $modelid = vehicle_units::where('id',$veh->vehicle_id)->value('model_id');
    $stdhrs = Std_working_hr::where([['model_id',$modelid],['shop_id',11]])->value('std_hors');

    $update = Unitmovement::where([['shop_id',11],['vehicle_id',$veh->vehicle_id]])->first();
    $update->std_hrs = $stdhrs;
    $update->save();
}

return $update;*/


			//dd(month_to_date_drr(10));
    //DRR Month to Date
        $today=Carbon::now();
        $startDate = Carbon::now(); //returns current day
        $firstDay = $startDate->firstOfMonth();
        $end=$today->format("Y-m-d");
        $start=$firstDay->format("Y-m-d");

        $endtwo=$today->format("Y-m-d H:i:s");

        $starttwo=$firstDay->format("Y-m-d 00:00:00");

        //year date
        $firstDayOfYear = $startDate->firstOfYear();
        $start_year=$firstDayOfYear->format("Y-m-d");
        $starttwo_year=$firstDayOfYear->format("Y-m-d 00:00:00");

        $master=array();

        $drl_plant_target= DrrTarget::where('active', 'Active')->where('target_type','Drl')->first();
        $master['drl_plant_target'] = 0;
        if(isset($drl_plant_target)){
            $master['drl_plant_target'] = $drl_plant_target->plant_target;
        }

        $plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->count();
        $shop = Shop::where('shop_name','DTC')->first();

        $vehicle_at_care = Unitmovement::where([['shop_id', '=', $shop->id]])->whereBetween('datetime_in',[$start,$end])->count();

        $plant_drl=0;
        if($vehicle_at_care>0){
        $plant_drl= round((($plant_defect/$vehicle_at_care)*100)) ;
        }
        $master['plant_drl']=round($plant_drl);

//Daily Drl Report
$daily_plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereDate('created_at',$end)->count();
$daily_vehicle_at_care = Unitmovement::where([['shop_id', '=', $shop->id]])->whereDate('datetime_in',$end)->count();
$daily_plant_drl=0;
if($daily_vehicle_at_care>0){
$daily_plant_drl= round((($daily_plant_defect/$daily_vehicle_at_care)*100)) ;
}
$master['daily_plant_drl']=round($daily_plant_drl);

//Year To Date Drl Report
$year_vehicle_at_care = Unitmovement::where([['shop_id', '=', $shop->id]])->whereBetween('datetime_in',[$start_year,$end])->count();

$year_plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo_year,$endtwo])->count();

$year_plant_drl=0;
if($year_vehicle_at_care>0){
$year_plant_drl= round((($year_plant_defect/$year_vehicle_at_care)*100)) ;
}
$master['year_plant_drl']=round($year_plant_drl);

//DRR
$pant_drr=[];
$pant_drr_pc=[];

  $shops = Shop::where('offline','=','1')->get();
     foreach($shops as $shoprow){
                $shop_id = $shoprow->id;
    $plant_total_units = Unitmovement::where([['shop_id', '=', $shop_id]])->whereBetween('datetime_in',[$start,$end])->count();

    $shop_id = $shoprow->id;

    $plant_total_defects = Drr::where([['shop_id', '=', $shop_id]])->whereBetween('created_at',[$starttwo,$endtwo])->count();

    $plant_ok_units=$plant_total_units-$plant_total_defects;


    $midscore=0;
            if($plant_total_units>0){
                $midscore=($plant_ok_units / $plant_total_units)*100;
           }


        $pant_drr[] =$midscore;
        $pant_drr_pc[]=100;
}

    $target_details = DrrTarget::where('active', 'Active')->where('target_type','Drr')->first();

$tt=(array_product($pant_drr)/array_product($pant_drr_pc))*100;
$pant_drr=round($tt,2);



$master['plant_drr']=round($pant_drr);
$master['plant_drr_target']= 0.0;
if(isset($target_details)){
    $master['plant_drr_target']=round(($target_details->plant_target),2);
}



//Daily Drr Report


$daily_pant_drr=[];
$daily_pant_drr_pc=[];

  $shops = Shop::where('offline','=','1')->get();
     foreach($shops as $shoprow){

          $shop_id = $shoprow->id;
          $daily_plant_total_units = Unitmovement::where([['shop_id', '=', $shop_id]])->whereDate('datetime_in',$end)->count();

          $shop_id = $shoprow->id;

            $daily_plant_total_defects = Drr::where([['shop_id', '=', $shop_id]])->whereDate('created_at',$end)->count();

 $daily_plant_ok_units=$daily_plant_total_units-$daily_plant_total_defects;

       $daily_midscore=0;
            if($daily_plant_total_units>0){

        $daily_midscore=($daily_plant_ok_units / $daily_plant_total_units)*100;
           }

        $daily_pant_drr[] =$daily_midscore;
        $daily_pant_drr_pc[]=100;
        }

$daily_tt=(array_product($daily_pant_drr)/array_product($daily_pant_drr_pc))*100;
$daily_pant_drr=round($daily_tt,2);

$master['daily_plant_drr']=round($daily_pant_drr);


//Year To Date Drr Report
$year_pant_drr=[];
$year_pant_drr_pc=[];

  $shops = Shop::where('offline','=','1')->get();
     foreach($shops as $shoprow){

          $shop_id = $shoprow->id;
          $year_plant_total_units = Unitmovement::where([['shop_id', '=', $shop_id]])->whereBetween('datetime_in',[$start_year,$end])->count();

          $shop_id = $shoprow->id;

            $year_plant_total_defects = Drr::where([['shop_id', '=', $shop_id]])->whereBetween('created_at',[$starttwo_year,$endtwo])->count();

 $year_plant_ok_units=$year_plant_total_units-$year_plant_total_defects;


       $year_midscore=0;
            if($year_plant_total_units>0){

        $year_midscore=($year_plant_ok_units / $year_plant_total_units)*100;
           }

        $year_pant_drr[] =$year_midscore;
        $year_pant_drr_pc[]=100;
}

$year_tt=(array_product($year_pant_drr)/array_product($year_pant_drr_pc))*100;
$year_pant_drr=round($year_tt,2);

$master['year_plant_drr']=round($year_pant_drr);



//Month To Date Care
$shop = Shop::find(16);//MPC

  $care_total_units = Unitmovement::where([['shop_id', '=', $shop->id]])->whereBetween('datetime_in',[$start,$end])->count();

$care_total_defects = Drr::where([['shop_id', '=',$shop->id]])->whereBetween('created_at',[$starttwo,$endtwo])->count();

$care_total_drr=$care_total_units-$care_total_defects;

$care_midscore=0;
    if($care_total_units>0){
        $care_midscore=($care_total_drr / $care_total_units)*100;
        }
        $care_target_details = '0';
        if($target_details != '0'){
            //$target_details = 0;
            $care_target_details = DrrTargetShop::where('target_id', $target_details->id)->where('shop_id',$shop->id)->first();
        }


$master['care_midscore'] = round(($care_midscore),2);
$master['care_target_details'] = ($care_target_details == '0') ? 0 : round(($care_target_details->target_value),2);


//Daily Care

$shop = Shop::find(16);//MPC
$daily_care_total_units = Unitmovement::where([['shop_id', '=', $shop->id]])->whereDate('datetime_in',$end)->count();


$daily_care_total_defects = Drr::where([['shop_id', '=',$shop->id]])->whereDate('created_at',$end)->count();

$daily_care_total_drr=$daily_care_total_units-$daily_care_total_defects;

$daily_care_midscore=0;
            if($daily_care_total_units>0){

        $daily_care_midscore=($daily_care_total_drr / $daily_care_total_units)*100;
           }

$master['daily_care_midscore']=round(($daily_care_midscore),2);

//Year To Date  Care

$shop = Shop::find(16);//MPC
$year_care_total_units = Unitmovement::where([['shop_id', '=', $shop->id]])->whereBetween('datetime_in',[$start_year,$end])->count();


$year_care_total_defects = Drr::where([['shop_id', '=',$shop->id]])->whereBetween('created_at',[$starttwo_year,$endtwo])->count();

$year_care_total_drr=$year_care_total_units-$year_care_total_defects;

$year_care_midscore=0;
            if($year_care_total_units>0){

        $year_care_midscore=($year_care_total_drr / $year_care_total_units)*100;
           }

$master['year_care_midscore']=round(($year_care_midscore),2);

//Repair Float Month to date
$shop = Shop::find(15);//MPB
$shop_id = $shop->id;
$date_from = $starttwo;
$date_to = $endtwo;


$wq = compact('shop_id', 'date_from', 'date_to');
    $mtd_repair_float = vehicle_units::whereHas('defects',function ($query) use( $wq) {
    $query->where([['is_defect', '=', 'Yes'],['shop_id', '=', $wq['shop_id']] ])->whereBetween('created_at',[$wq['date_from'],$wq['date_to']]);
})->count();

$master['mtd_repair_float']=round(($mtd_repair_float),2);

//Repair Float Year  to date
$shop = Shop::find(15);//MPB
$shop_id = $shop->id;
$date_from = $starttwo_year;
$date_to = $endtwo;


$wq = compact('shop_id', 'date_from', 'date_to');
    $ytd_repair_float = vehicle_units::whereHas('defects',function ($query) use( $wq) {
    $query->where([['is_defect', '=', 'Yes'],['shop_id', '=', $wq['shop_id']] ])->whereBetween('created_at',[$wq['date_from'],$wq['date_to']]);
})->count();


$master['ytd_repair_float']=round(($ytd_repair_float),2);


//Repair Float Today
$shop = Shop::find(15);//MPB
$shop_id = $shop->id;
$today = $end;

$wq = compact('shop_id', 'today');
    $today_repair_float = vehicle_units::whereHas('defects',function ($query) use( $wq) {
    $query->where([['is_defect', '=', 'Yes'],['shop_id', '=', $wq['shop_id']] ])->whereDate('created_at',$wq['today']);
})->count();

$master['today_repair_float'] = round(($today_repair_float),2);


//PEOPLE SECTION
$today = Carbon::yesterday()->format('Y-m-d');
$first = Carbon::create($today)->startOfMonth()->format('Y-m-d');
$yesterday = carbon::yesterday()->format('Y-m-d');
$firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
$firstthismonthY = Carbon::create($yesterday)->startOfMonth()->format('Y-m-d');
$shops = Shop::where('check_shop','=','1')->get(['id','report_name']);

$plantabb = getTarget($yesterday)->absentieesm;
$efftag = getTarget($yesterday)->efficiency;
$planttlav = getplantTLAtarget();

//EFFICIENCY
$master['plant_eff'] = round(getPlantEfficiency($firstthismonthY, $yesterday),2);
$master['efftag'] = round($efftag,0);


//ABSENTIEESM
$allschdates = Production_target::whereBetween('date',[$firstthismonthY,$yesterday])
                    ->groupby('date')->get(['date']);
$tthours = 0;
$ttattend = 0;
foreach($allschdates as $schdt){
    $hrs = Attendance::where('date', '=', $schdt->date)
                ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
    $tthours += $hrs;
    $noofemp = Attendance::where('date','=', $schdt->date)->count();
    $ttattend += $noofemp;
}

    $exphrs = $ttattend*8;

    $absent = $exphrs - $tthours;

    ($absent > 0) ? $absentiesm = round((($absent/$exphrs)*100),2) : $absentiesm = 0;

    $master['absentiesm'] = round($absentiesm,2);
    $master['plantabb'] = round($plantabb,0);


//TEAMLEADER AVAILABILITY
    $direct =  DB::table('attendances')
        ->join('employees', 'employees.id', '=', 'attendances.staff_id')
        ->whereBetween('date', [$firstthismonthY, $yesterday])->where('team_leader','=','yes')
        ->sum(DB::raw('direct_hrs'));
    $indirect =  DB::table('attendances')
        ->join('employees', 'employees.id', '=', 'attendances.staff_id')
        ->whereBetween('date', [$firstthismonthY, $yesterday])->where('team_leader','=','yes')
        ->sum(DB::raw('indirect_hrs + loaned_hrs'));

$tthrs = ($indirect+$direct == 0) ? 1 : $indirect+$direct;

$TLavail = ($tthrs != 1) ? round(($indirect/$tthrs)*100,2) : '--';

$master['TLavail'] = round($TLavail,2);
$master['planttlav'] = round($planttlav,0);


//OFFLINE
$today = carbon::now()->format('Y-m-d');
$offline = UnitMovement::whereBetween('datetime_out',[$first, $today])->where('shop_id','=',8)->count() + UnitMovement::whereBetween('datetime_out',[$first, $today])->where('shop_id','=',10)->count() + UnitMovement::whereBetween('datetime_out',[$first, $today])->where('shop_id','=',13)->count();
$offtarget = Production_target::whereBetween('date',[$first, $today])->where('level','=','offline')->sum('noofunits');
$master['offline'] = $offline;
$master['offtarget'] = $offtarget;
$master['offvar'] = $master['offline'] - $master['offtarget'];



//FCW

$fcw = UnitMovement::whereBetween('datetime_out',[$first, $today])
                    ->where('shop_id','=',16)->count();
$master['actual'] = $fcw;
$fcwlcvtarget = Production_target::whereBetween('lcv',[$first, $today])->sum('noofunits');
$fcwcvtarget = Production_target::whereBetween('cv',[$first, $today])->sum('noofunits');

$master['fcwtarget'] = $fcwlcvtarget + $fcwcvtarget; //floor($master['offtarget']/2);
$master['fcwvarience'] = $master['actual'] - $master['fcwtarget'];

//GCA MTD
$cvid = GcaScore::where('lcv_cv','=','cv')->max('id'); $lcvid = GcaScore::where('lcv_cv','=','lcv')->max('id');
$master['cvwdpv'] = round(GcaScore::where('id','=',$cvid)->value('mtdwdpv'),0);
$master['lcvwdpv'] = round(GcaScore::where('id','=',$lcvid)->value('mtdwdpv'),0);

$cvdefects = GcaScore::where('id','=',$cvid)->sum(DB::raw('defectcar1 + defectcar1'));
$lcvdefects = GcaScore::where('id','=',$lcvid)->sum(DB::raw('defectcar1 + defectcar1'));


$master['cvdpv'] = round(($cvdefects/GcaScore::where('id','=',$cvid)->value('units_sampled')),0);
$master['lcvdpv'] = round(($lcvdefects/GcaScore::where('id','=',$lcvid)->value('units_sampled')),0);

$master['cvdpvtarget'] = (getGCATarget($today) == '0') ? 0 : round(getGCATarget($today)->cvdpv,1);
$master['cvwdpvtarget'] = (getGCATarget($today) == '0') ? 0 : round(getGCATarget($today)->cvwdpv,1);
$master['lcvdpvtarget'] = (getGCATarget($today) == '0') ? 0 : round(getGCATarget($today)->lcvdpv,1);
$master['lcvwdpvtarget'] = (getGCATarget($today) == '0') ? 0 : round(getGCATarget($today)->lcvwdpv,1);

//ADMINISTATIVE WARNINGD

//UNLOGGED ATTENDANCE
$allschdates = Production_target::whereBetween('date', ["2022-01-01", $yesterday])
                        ->groupby('date')->get(['date']);
$efshops = Shop::where('check_shop','=','1')->get(['report_name','id']);
unset($efshops[9]);

$unlogged = 0;
foreach($allschdates as $date){
    foreach($efshops as $shop){
        $logged = Attendance::where('shop_id',$shop->id)->where('date',$date->date)->get();
        if(count($logged) == 0){
            $unlogged = $unlogged + 1;
        }
    }

}
$master['unlogged'] = $unlogged;

//FCW SCHEDULE
$firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
$endthismonth = Carbon::now()->endOfMonth()->format('Y-m-d');
$fcwscheduled = 0;
$offlinescheduled = 0;
while($firstthismonth <= $endthismonth){
    $existfcw = Production_target::where([['level','fcw'],['date',$firstthismonth]])->first();
    if(isset($existfcw)){
        $fcwscheduled = 1;
    }

    $existoff = Production_target::where([['level','offline'],['date',$firstthismonth]])->first();
    if(isset($existoff)){
        $offlinescheduled = 1;
    }
    $firstthismonth = Carbon::parse($firstthismonth)->addDays(1)->format('Y-m-d');
}

$master['offscheduled'] = $offlinescheduled;
$master['fcwscheduled'] = $fcwscheduled;

//DELAYED UNITS
$today = carbon::today()->format("Y-m-d");
$master['delayed'] = Unitmovement::where([['datetime_in','!=',$today],['current_shop','>',0]])->count();


return view('home.index')->with(compact('master'));
}



public function quality_control_dashboard()
    {
//DRR Month to Date

$today=Carbon::now();
$startDate = Carbon::now(); //returns current day
$firstDay = $startDate->firstOfMonth();
$end=$today->format("Y-m-d");
$start=$firstDay->format("Y-m-d");


$endtwo=$today->format("Y-m-d H:i:s");

$starttwo=$firstDay->format("Y-m-d 00:00:00");

//year date
$firstDayOfYear = $startDate->firstOfYear();
$start_year=$firstDayOfYear->format("Y-m-d");
$starttwo_year=$firstDayOfYear->format("Y-m-d 00:00:00");

$master=array();


$drl_plant_target= DrrTarget::where('active', 'Active')->where('target_type','Drl')->first();

$master['drl_plant_target'] = $drl_plant_target->plant_target;
$plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo,$endtwo])->count();
$shop = Shop::where('shop_name','DTC')->first();

$vehicle_at_care = Unitmovement::where([['shop_id', '=', $shop->id]])->whereBetween('datetime_in',[$start,$end])->count();

$plant_drl=0;
if($vehicle_at_care>0){
$plant_drl= round((($plant_defect/$vehicle_at_care)*100)) ;
}
$master['plant_drl']=round($plant_drl);









//Daily Drl Report


$daily_plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereDate('created_at',$end)->count();
$daily_vehicle_at_care = Unitmovement::where([['shop_id', '=', $shop->id]])->whereDate('datetime_in',$end)->count();
$daily_plant_drl=0;
if($daily_vehicle_at_care>0){
$daily_plant_drl= round((($daily_plant_defect/$daily_vehicle_at_care)*100)) ;
}
$master['daily_plant_drl']=round($daily_plant_drl);

//Year To Date Drl Report

$year_vehicle_at_care = Unitmovement::where([['shop_id', '=', $shop->id]])->whereBetween('datetime_in',[$start_year,$end])->count();

$year_plant_defect = Querydefect::where([['is_defect', '=', 'Yes']])->whereBetween('created_at',[$starttwo_year,$endtwo])->count();

$year_plant_drl=0;
if($year_vehicle_at_care>0){
$year_plant_drl= round((($year_plant_defect/$year_vehicle_at_care)*100)) ;
}


$master['year_plant_drl']=round($year_plant_drl);



//DRR

$pant_drr=[];
$pant_drr_pc=[];

  $shops = Shop::where('offline','=','1')->get();
     foreach($shops as $shoprow){
                $shop_id = $shoprow->id;
    $plant_total_units = Unitmovement::where([['shop_id', '=', $shop_id]])->whereBetween('datetime_in',[$start,$end])->count();

    $shop_id = $shoprow->id;

    $plant_total_defects = Drr::where([['shop_id', '=', $shop_id]])->whereBetween('created_at',[$starttwo,$endtwo])->count();

    $plant_ok_units=$plant_total_units-$plant_total_defects;


    $midscore=0;
            if($plant_total_units>0){

    $midscore=($plant_ok_units / $plant_total_units)*100;
           }


        $pant_drr[] =$midscore;
        $pant_drr_pc[]=100;
    }

    $target_details = DrrTarget::where('active', 'Active')->where('target_type','Drr')->first();

$tt=(array_product($pant_drr)/array_product($pant_drr_pc))*100;
$pant_drr=round($tt,2);



$master['plant_drr']=round($pant_drr);
$master['plant_drr_target']=round(($target_details->plant_target),2);


//Daily Drr Report


$daily_pant_drr=[];
$daily_pant_drr_pc=[];

  $shops = Shop::where('offline','=','1')->get();
     foreach($shops as $shoprow){

          $shop_id = $shoprow->id;
          $daily_plant_total_units = Unitmovement::where([['shop_id', '=', $shop_id]])->whereDate('datetime_in',$end)->count();

          $shop_id = $shoprow->id;

            $daily_plant_total_defects = Drr::where([['shop_id', '=', $shop_id]])->whereDate('created_at',$end)->count();

 $daily_plant_ok_units=$daily_plant_total_units-$daily_plant_total_defects;


       $daily_midscore=0;
            if($daily_plant_total_units>0){

        $daily_midscore=($daily_plant_ok_units / $daily_plant_total_units)*100;
           }


        $daily_pant_drr[] =$daily_midscore;
        $daily_pant_drr_pc[]=100;

    }


$daily_tt=(array_product($daily_pant_drr)/array_product($daily_pant_drr_pc))*100;
$daily_pant_drr=round($daily_tt,2);


$master['daily_plant_drr']=round($daily_pant_drr);


//Year To Date Drr Report
$year_pant_drr=[];
$year_pant_drr_pc=[];

  $shops = Shop::where('offline','=','1')->get();
     foreach($shops as $shoprow){

          $shop_id = $shoprow->id;
          $year_plant_total_units = Unitmovement::where([['shop_id', '=', $shop_id]])->whereBetween('datetime_in',[$start_year,$end])->count();

          $shop_id = $shoprow->id;

            $year_plant_total_defects = Drr::where([['shop_id', '=', $shop_id]])->whereBetween('created_at',[$starttwo_year,$endtwo])->count();

 $year_plant_ok_units=$year_plant_total_units-$year_plant_total_defects;

       $year_midscore=0;
            if($year_plant_total_units>0){

        $year_midscore=($year_plant_ok_units / $year_plant_total_units)*100;
           }

        $year_pant_drr[] =$year_midscore;
        $year_pant_drr_pc[]=100;
}




$year_tt=(array_product($year_pant_drr)/array_product($year_pant_drr_pc))*100;
$year_pant_drr=round($year_tt,2);


$master['year_plant_drr']=round($year_pant_drr);





//Month To Date Care

$shop = Shop::find(16);//MPC

  $care_total_units = Unitmovement::where([['shop_id', '=', $shop->id]])->whereBetween('datetime_in',[$start,$end])->count();

$care_total_defects = Drr::where([['shop_id', '=',$shop->id]])->whereBetween('created_at',[$starttwo,$endtwo])->count();

$care_total_drr=$care_total_units-$care_total_defects;

$care_midscore=0;
    if($care_total_units>0){
        $care_midscore=($care_total_drr / $care_total_units)*100;
    }


            $care_target_details = DrrTargetShop::where('target_id', $target_details->id)->where('shop_id',$shop->id)->first();

$master['care_midscore']=round(($care_midscore),2);
$master['care_target_details']=round(($care_target_details->target_value),2);


//Daily Care

$shop = Shop::find(16);//MPC
$daily_care_total_units = Unitmovement::where([['shop_id', '=', $shop->id]])->whereDate('datetime_in',$end)->count();


$daily_care_total_defects = Drr::where([['shop_id', '=',$shop->id]])->whereDate('created_at',$end)->count();

$daily_care_total_drr=$daily_care_total_units-$daily_care_total_defects;

$daily_care_midscore=0;
            if($daily_care_total_units>0){

        $daily_care_midscore=($daily_care_total_drr / $daily_care_total_units)*100;
           }

$master['daily_care_midscore']=round(($daily_care_midscore),2);

//Year To Date  Care

$shop = Shop::find(16);//MPC
$year_care_total_units = Unitmovement::where([['shop_id', '=', $shop->id]])->whereBetween('datetime_in',[$start_year,$end])->count();


$year_care_total_defects = Drr::where([['shop_id', '=',$shop->id]])->whereBetween('created_at',[$starttwo_year,$endtwo])->count();

$year_care_total_drr=$year_care_total_units-$year_care_total_defects;

$year_care_midscore=0;
            if($year_care_total_units>0){

        $year_care_midscore=($year_care_total_drr / $year_care_total_units)*100;
           }

$master['year_care_midscore']=round(($year_care_midscore),2);



//Repair Float Month to date
$shop = Shop::find(15);//MPB
$shop_id = $shop->id;
$date_from = $starttwo;
$date_to = $endtwo;


$wq = compact('shop_id', 'date_from', 'date_to');
    $mtd_repair_float = vehicle_units::whereHas('defects',function ($query) use( $wq) {
    $query->where([['is_defect', '=', 'Yes'],['shop_id', '=', $wq['shop_id']] ])->whereBetween('created_at',[$wq['date_from'],$wq['date_to']]);
})->count();


$master['mtd_repair_float']=round(($mtd_repair_float),2);



//Repair Float Year  to date
$shop = Shop::find(15);//MPB
$shop_id = $shop->id;
$date_from = $starttwo_year;
$date_to = $endtwo;


$wq = compact('shop_id', 'date_from', 'date_to');
    $ytd_repair_float = vehicle_units::whereHas('defects',function ($query) use( $wq) {
    $query->where([['is_defect', '=', 'Yes'],['shop_id', '=', $wq['shop_id']] ])->whereBetween('created_at',[$wq['date_from'],$wq['date_to']]);
})->count();


$master['ytd_repair_float']=round(($ytd_repair_float),2);


//Repair Float Today
$shop = Shop::find(15);//MPB
$shop_id = $shop->id;
$today = $end;



$wq = compact('shop_id', 'today');
    $today_repair_float = vehicle_units::whereHas('defects',function ($query) use( $wq) {
    $query->where([['is_defect', '=', 'Yes'],['shop_id', '=', $wq['shop_id']] ])->whereDate('created_at',$wq['today']);
})->count();

$master['today_repair_float']=round(($today_repair_float),2);



        return view('home.quality')->with(compact('master'));
    }

}
