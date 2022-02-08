<?php

namespace App\Http\Controllers\screenboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\shop\Shop;
use App\Models\productiontarget\Production_target;
use App\Models\unitmovement\Unitmovement;
use App\Models\attendance\Attendance;
use App\Models\indivtarget\IndivTarget;
use App\Models\gcascore\GcaScore;
use App\Models\employee\Employee;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use App\Models\vehicle_units\vehicle_units;
use Yajra\DataTables\Facades\DataTables;
use App\Models\querydefect\Querydefect;
use App\Models\gcatarget\GcaTarget;


class ScreenboardController extends Controller
{
    public function screenboard(){
        $shops = Shop::where('check_point','=',1)->orderby('shop_no')->get(['id','shop_name']);
        unset($shops[7]); //Remove inline F-Series
        unset($shops[5]); //Remove inline N-Series
        unset($shops[12]); //Remove inline N-Series

        $data = array(
            'sections'=>$shops,
        );
        return view('screenboard.main')->with($data);
    }

     public function screenboardindex(Request $request){

        /*return Employee::where([['team_leader','=','yes'],['status','=','Active']])->with(['attendance'=>function($quey) use ($firstthismonth, $today) {
            $quey->whereBetween('date', [$firstthismonth, $today]);}])->get('attendance:othours');//->sum('indirect_hrs');

        $tls = Employee::where('team_leader','=','yes')->find('id');
        return $hours = $tls->attendance()->get()->sum('id');*/

        $section = $request->input('section');
        $shifthrs = $request->input('shift');
        $today = carbon::today()->format('Y-m-d');
        $yesterday = carbon::yesterday()->format('Y-m-d');
        $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $firstthismonthY = Carbon::create($yesterday)->startOfMonth()->format('Y-m-d');

        $efshops = Shop::where('check_shop','=','1')->get(['report_name','id']);
         unset($efshops[9]);

        if($section == 'plant'){$sectionname = "PLANT";
        //PLANT SCREEN

        //Plant efficiency TODAY
        $tddrhrs = Attendance::where('date','=',$yesterday)->sum(DB::raw('efficiencyhrs'));
        $tdlnhrs = Attendance::where('date','=',$yesterday)->sum(DB::raw('loaned_hrs'));
        $tdotlnhrs = Attendance::where('date','=',$yesterday)->sum(DB::raw('otloaned_hrs'));
        $TDinput = $tddrhrs + $tdlnhrs + $tdotlnhrs;

        $TDoutput = Unitmovement::where('datetime_out','=',$yesterday)->sum('std_hrs');
        $TDplant_eff = ($TDinput > 0) ? round(($TDoutput/$TDinput)*100,0) : 0;

        //MTD EFFICIENCY
        $MTDplant_eff = getPlantEfficiency($firstthismonthY, $yesterday);//($MTDinput > 0) ? round(($MTDoutput/$MTDinput)*100,0) : 0;
        $planteff_target = round(getTarget($firstthismonthY)->efficiency,0);

        //Plant FCW today
        $TDfcw = Unitmovement::where([['datetime_out','=',$today],['shop_id','=',16]])->count();
        $TDfcwtarget = Production_target::where([['date','=',$today],['level','=','fcw']])->sum('noofunits');

        //Plant FCW MTD
        $MTDfcw = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',16)->count();
        $MTDfcwtarget = Production_target::whereBetween('date', [$firstthismonth, $today])->where('level','=','fcw')->sum('noofunits');

        //Plant Offline today
        $TDoffline = Unitmovement::where([['datetime_out','=',$today],['shop_id','=',8]])->count() + Unitmovement::where([['datetime_out','=',$today],['shop_id','=',10]])->count() + Unitmovement::where([['datetime_out','=',$today],['shop_id','=',13]])->count();
        $TDofflinetarget = Production_target::where([['date','=',$today],['level','=','offline']])->sum('noofunits');

        //Plant Offline MTD
        $MTDoffline = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',8)->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',10)->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',13)->count();
        $MTDofflinetarget = Production_target::whereBetween('date', [$firstthismonth, $today])->where('level','=','offline')->sum('noofunits');

        //GCA MTD
        $cvid = GcaScore::where('lcv_cv','=','cv')->max('id'); $lcvid = GcaScore::where('lcv_cv','=','lcv')->max('id');
        $cvwdpv = round(GcaScore::where('id','=',$cvid)->value('mtdwdpv'),2);
        $lcvwdpv = round(GcaScore::where('id','=',$lcvid)->value('mtdwdpv'),2);

        $cvdefects = GcaScore::where('id','=',$cvid)->sum(DB::raw('defectcar1 + defectcar1'));
        $lcvdefects = GcaScore::where('id','=',$lcvid)->sum(DB::raw('defectcar1 + defectcar1'));


        $cvdpv = round(($cvdefects/GcaScore::where('id','=',$cvid)->value('units_sampled')),2);
        $lcvdpv = round(($lcvdefects/GcaScore::where('id','=',$lcvid)->value('units_sampled')),2);


        $cvdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->cvdpv,1);
        $cvwdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->cvwdpv,1);
        $lcvdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->lcvdpv,1);
        $lcvwdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->lcvwdpv,1);

        //TEAMLEADER AVAILABILITY
        //Today
        $plantTThrs =0; $plantindirect = 0;
        foreach($efshops as $shop){
            $direct = 0; $indirect = 0;
            $teamleaders = Employee::where([['team_leader','=','yes'],['shop_id', '=', $shop->id],['status','=','Active']])->get('id');
            foreach($teamleaders as $tl){
                $direct += Attendance::where([['staff_id','=',$tl->id],['date', '=', $yesterday]])
                            ->sum(DB::raw('direct_hrs + othours'));
                $indirect += Attendance::where([['staff_id','=',$tl->id],['date', '=', $yesterday]])
                            ->sum(DB::raw('indirect_hrs + indirect_othours'));
            }
            $plantTThrs += $indirect+$direct;
            $plantindirect += $indirect;
        }

        $TDTLavail = ($plantTThrs > 0) ? round(($plantindirect/$plantTThrs)*100,0) : 0;

        //MTD T/L Availability
        $plantTThrs =0; $plantdirect = 0;
        foreach($efshops as $shop){
            $teamleaders = Employee::where([['team_leader','=','yes'],['shop_id', '=', $shop->id],['status','=','Active']])->get('id');
            foreach($teamleaders as $tl){
                $direct = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])
                        ->where('staff_id','=',$tl->id)->sum(DB::raw('direct_hrs + othours'));
                $indirect = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])
                        ->where('staff_id','=',$tl->id)->sum(DB::raw('indirect_hrs + indirect_othours'));
                $plantTThrs += $direct + $indirect;
                $plantdirect += $indirect;
            }
        }
        $MTDTLavail = ($plantTThrs == 0) ? 0 : round(($plantdirect/$plantTThrs)*100,2);
        $plantTL_target = round(getplantTLAtarget(),0);

        //ABSENTEEISM
        //Yesterday absenteeism
        $empcount = Attendance::where('date','=',$yesterday)->count();
        //if($empcount != null){
            $expectedhrs = $empcount * 8;
            $hrsworked = Attendance::where('date','=',$yesterday)
                            ->sum(DB::raw('direct_hrs + indirect_hrs'));
            $absent = $expectedhrs - $hrsworked;
            ($absent > 0) ? $TDabsentiesm = round(((($absent)/$expectedhrs)*100),0) : $TDabsentiesm = 0;
        //}

        //MTD absenteeism
        $empcount = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->count();
        if($empcount != null){
            $expectedhrs = $empcount * 8;
            $hrsworked = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])
                            ->sum(DB::raw('direct_hrs + indirect_hrs'));
            $absent = $expectedhrs - $hrsworked;
            ($absent > 0) ? $MTDabsentiesm = round(((($absent)/$expectedhrs)*100),0) : $MTDabsentiesm = 0;
        }
        $plantAB_target = round(getTarget($firstthismonthY)->absentieesm,0);

        //DRL & DRR
        $TDdrl = drl_today()['drl'];
        $TDdrltarget = drl_today()['drl_target_value'];
        $MTDdrl = month_to_date_drl()['drl'];
        $MTDdrltarget = month_to_date_drl()['drl_target_value'];

        $TDdrr = today_drr()['plant_drr'];
        $TDdrrtarget = today_drr()['drr_target_value'];
        $MTDdrr = month_to_date_drr()['plant_drr'];
        $MTDdrrtarget = month_to_date_drr()['drr_target_value'];

    }


    elseif($section == 'cv' || $section == 'lcv'){
        //CV & LCV SCREEN
        if($section == 'cv'){
            $sectionname = "CV";
            $shops = Shop::where('lcvcv_share','cv')->orwhere('lcvcv_share','share')->get(['id','shop_name']);
            unset($shops[3]);
            unset($shops[6]);
            unset($shops[8]);
        }else{
            $sectionname = "LCV";
             $shops = Shop::where('lcvcv_share','lcv')->get(['id','shop_name']);
             unset($shops[1]);
        }

        $ttinputhrs = 0; $ttoutputhrs = 0; $MTDinputhrs = 0; $MTDoutputhrs = 0; $MTDttindirect = 0; $TDttindirect = 0;
        $TDdirect = 0; $TDindirect = 0; $TDtthrs = 0; $MTDdirect = 0; $MTDindirect = 0; $MTDtthrs = 0;
        $TDempcount = 0; $MTDempcount = 0; $TDhrsworked = 0; $MTDhrsworked = 0;
        foreach($shops as $sp){
            //Plant efficiency TODAY
            $efftthrs = Attendance::where([['date',$yesterday],['shop_id',$sp->id]])->sum(DB::raw('efficiencyhrs'));
            $spmtdlnhrs = Attendance::where([['date',$yesterday],['shop_loaned_to',$sp->id]])->sum(DB::raw('loaned_hrs'));
            $spmtdotlnhrs = Attendance::where([['date',$yesterday],['shop_loaned_to',$sp->id]])->sum(DB::raw('otloaned_hrs'));
            $suminputhrs = $efftthrs + $spmtdlnhrs + $spmtdotlnhrs;
            $ttinputhrs += $suminputhrs;

            $ttoutputhrs += Unitmovement::where([['datetime_out',$yesterday],['shop_id',$sp->id]])->sum('std_hrs');

            //Plant efficiency MTD
            $MTDinputhrs += Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$sp->id)->sum(DB::raw('efficiencyhrs + loaned_hrs + otloaned_hrs'));
            $MTDoutputhrs += Unitmovement::whereBetween('datetime_out', [$firstthismonthY, $yesterday])->where('shop_id',$sp->id)->sum('std_hrs');

            //TEAMLEADER AVAILABILITY
            $teamleaders = Employee::where([['team_leader','=','yes'],['status','=','Active'],['shop_id',$sp->id]])->get('id');
            foreach($teamleaders as $tl){
                //Today
                $TDdirect = Attendance::where([['staff_id','=',$tl->id],['date', '=', $yesterday]])
                            ->sum(DB::raw('direct_hrs + othours'));
                $TDindirect = Attendance::where([['staff_id','=',$tl->id],['date', '=', $yesterday]])
                            ->sum(DB::raw('indirect_hrs + indirect_othours'));
                $TDtthrs += $TDdirect + $TDindirect;
                $TDttindirect += $TDindirect;

                //MTD
                $MTDdirect = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('staff_id','=',$tl->id)
                        ->sum(DB::raw('direct_hrs + othours'));
                $MTDindirect = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('staff_id','=',$tl->id)
                        ->sum(DB::raw('indirect_hrs + indirect_othours'));
                $MTDtthrs += $MTDdirect + $MTDindirect;
                $MTDttindirect += $MTDindirect;
            }

            //ABSENTEEISM
            //Yesterday absenteeism
            $TDempcount += Attendance::where([['date','=',$yesterday],['shop_id',$sp->id]])->count();
            $TDhrsworked += Attendance::where([['date','=',$yesterday],['shop_id',$sp->id]])->sum(DB::raw('direct_hrs + indirect_hrs'));
            //MTD absenteeism
            $MTDempcount += Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$sp->id)->count();
            $MTDhrsworked += Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$sp->id)->sum(DB::raw('direct_hrs + indirect_hrs'));
        }

        $TDTLavail = ($TDtthrs > 0) ? round(($TDttindirect/$TDtthrs)*100,0) : 0;

        $MTDTLavail = ($MTDtthrs > 0) ? round(($MTDttindirect/$MTDtthrs)*100,0) : 0;
        $plantTL_target = round(getplantTLAtarget(),0);

        $TDexpectedhrs = $TDempcount * 8;    $TDabsent = $TDexpectedhrs - $TDhrsworked;
        ($TDabsent > 0) ? $TDabsentiesm = round(((($TDabsent)/$TDexpectedhrs)*100),0) : $TDabsentiesm = 0;

        $MTDexpectedhrs = $MTDempcount * 8;     $MTDabsent = $MTDexpectedhrs - $MTDhrsworked;
        ($MTDabsent > 0) ? $MTDabsentiesm = round(((($MTDabsent)/$MTDexpectedhrs)*100),0) : $MTDabsentiesm = 0;
        $plantAB_target = round(getTarget($firstthismonthY)->absentieesm,0);

        $TDplant_eff = ($ttinputhrs > 0) ? round(($ttoutputhrs/$ttinputhrs)*100,0) : 0;
        $MTDplant_eff = ($MTDinputhrs > 0) ? round(($MTDoutputhrs/$MTDinputhrs)*100,0) : 0;
        $planteff_target = round(getTarget($firstthismonthY)->efficiency,0);

        //FCW MTD and today
        if($section == 'cv'){
            //FCW
            $TDfcw = Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',1]])->count() + Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',2]])->count() + Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',3]])->count();
            $MTDfcw = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',1]])->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',2]])->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',3]])->count();
            $TDfcwtarget = Production_target::where([['cv','=',$today],['level','=','fcw']])->sum('noofunits');
            $MTDfcwtarget = Production_target::whereBetween('cv', [$firstthismonth, $today])->where('level','=','fcw')->sum('noofunits');

            //OFFLINE
            $TDoffline = Unitmovement::where([['datetime_out','=',$today],['shop_id','=',8]])->count() + Unitmovement::where([['datetime_out','=',$today],['shop_id','=',10]])->count();
            $TDofflinetarget = Production_target::where([['date','=',$today],['level','=','offline'],['route_id',1]])
                    ->orWhere(function ($query) use ($today) {
                        $query->where([['date','=',$today],['level','=','offline'],['route_id',3]]);
                        })->orWhere(function ($query) use ($today) {
                        $query->where([['date','=',$today],['level','=','offline'],['route_id',5]]);
                        })->sum('noofunits');

			//Production_target::where([['date','=',$today],['level','=','offline'],['route_id',1]])->sum('noofunits');
            $MTDoffline = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',8)->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',10)->count();
            $MTDofflinetarget  = Production_target::whereBetween('date',[$firstthismonth, $today])->where([['level','=','offline'],['route_id',1]])
                    ->orWhere(function ($query) use ($firstthismonth, $today) {
                        $query->where([['level','=','offline'],['route_id',3]])
                            ->whereBetween('date',[$firstthismonth, $today]);
                        })->orWhere(function ($query) use ($firstthismonth, $today) {
                        $query->where([['level','=','offline'],['route_id',5]])
                            ->whereBetween('date',[$firstthismonth, $today]);
                        })->sum('noofunits');
            //CV GCA
            $cvid = GcaScore::where('lcv_cv','=','cv')->max('id');
            $cvwdpv = round(GcaScore::where('id','=',$cvid)->value('mtdwdpv'),2);
            $cvdefects = GcaScore::where('id','=',$cvid)->sum(DB::raw('defectcar1 + defectcar1'));
            $cvdpv = round(($cvdefects/GcaScore::where('id','=',$cvid)->value('units_sampled')),2);
            $maxid = GcaTarget::max('id');
            $cvdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->cvdpv,1);
            $cvwdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->cvwdpv,1);
            $lcvdpv = 0; $lcvdpvtarget = 0; $lcvwdpv = 0; $lcvwdpvtarget = 0;

            //DRL & DRR
            $TDdrl = drl_today()['drl'];
            $TDdrltarget = drl_today()['drl_target_value'];
            $MTDdrl = month_to_date_drl()['drl'];
            $MTDdrltarget = month_to_date_drl()['drl_target_value'];

            $TDdrr = today_drr()['plant_drr'];
            $TDdrrtarget = today_drr()['drr_target_value'];
            $MTDdrr = month_to_date_drr()['plant_drr'];
            $MTDdrrtarget = month_to_date_drr()['drr_target_value'];
        }else{
            //FCW
            $TDfcw = Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',5]])->count() + Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',4]])->count();
            $MTDfcw = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',4]])->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',5]])->count();
            $TDfcwtarget = Production_target::where([['lcv','=',$today],['level','=','fcw']])->sum('noofunits');
            $MTDfcwtarget = Production_target::whereBetween('lcv', [$firstthismonth, $today])->where('level','=','fcw')->sum('noofunits');

            //OFFLINE
            $TDoffline = Unitmovement::where([['datetime_out','=',$today],['shop_id','=',13]])->count();
            $TDofflinetarget = Production_target::where([['date','=',$today],['level','=','offline'],['route_id',7]])
                    ->orWhere(function ($query) use ($today) {
                        $query->where([['date','=',$today],['level','=','offline'],['route_id',9]]);
                        })->sum('noofunits');

			//Production_target::where([['lcv','=',$today],['level','=','offline']])->sum('noofunits');
            $MTDoffline = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',13)->count();

            $MTDofflinetarget  = Production_target::whereBetween('date',[$firstthismonth, $today])->where([['level','=','offline'],['route_id',7]])
                    ->orWhere(function ($query) use ($firstthismonth, $today) {
                        $query->where([['level','=','offline'],['route_id',9]])
                            ->whereBetween('date',[$firstthismonth, $today]);
                        })->sum('noofunits');


            //LCV GCA
            $lcvid = GcaScore::where('lcv_cv','=','lcv')->max('id');
            $lcvwdpv = round(GcaScore::where('id','=',$lcvid)->value('mtdwdpv'),2);
            $lcvdefects = GcaScore::where('id','=',$lcvid)->sum(DB::raw('defectcar1 + defectcar1'));
            $lcvdpv = round(($lcvdefects/GcaScore::where('id','=',$lcvid)->value('units_sampled')),2);


            $lcvdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->lcvdpv,1);
            $lcvwdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->lcvwdpv,1);
            $cvdpv = 0; $cvdpvtarget = 0; $cvwdpv = 0; $cvwdpvtarget = 0;

            //DRL & DRR
            $TDdrl = drl_today()['drl'];
            $TDdrltarget = drl_today()['drl_target_value'];
            $MTDdrl = month_to_date_drl()['drl'];
            $MTDdrltarget = month_to_date_drl()['drl_target_value'];

            $TDdrr = today_drr()['plant_drr'];
            $TDdrrtarget = today_drr()['drr_target_value'];
            $MTDdrr = month_to_date_drr()['plant_drr'];
            $MTDdrrtarget = month_to_date_drr()['drr_target_value'];
        }

 }

    $data = array(

        'time' => Carbon::now()->format('g:i:s A'),
        'shifthrs'=>$shifthrs,
        'sectionname'=>$sectionname,

        'TDabsentiesm'=>$TDabsentiesm,
        'MTDabsentiesm'=>$MTDabsentiesm,
        'plantAB_target'=>$plantAB_target,

        'TDTLavail'=>$TDTLavail,
        'MTDTLavail'=>$MTDTLavail,
        'plantTL_target'=>$plantTL_target,

        'cvdpv'=>$cvdpv,
        'lcvdpv'=>$lcvdpv,
        'cvwdpv'=>$cvwdpv,
        'lcvwdpv'=>$lcvwdpv,

        'cvdpvtarget'=>round($cvdpvtarget,1),
        'cvwdpvtarget'=>round($cvwdpvtarget,1),
        'lcvdpvtarget'=>round($lcvdpvtarget,1),
        'lcvwdpvtarget'=>round($lcvwdpvtarget,1),

        'MTDoffline'=>$MTDoffline,
        'TDoffline'=>$TDoffline,
        'MTDofflinetarget'=>$MTDofflinetarget,
        'TDofflinetarget'=>$TDofflinetarget,

        'MTDfcw'=>$MTDfcw,
        'TDfcw'=>$TDfcw,
        'MTDfcwtarget'=>$MTDfcwtarget,
        'TDfcwtarget'=>$TDfcwtarget,

        'TDplant_eff'=>$TDplant_eff,
        'MTDplant_eff'=>$MTDplant_eff,
        'planteff_target'=>$planteff_target,

        'TDdrl'=>$TDdrl,
        'TDdrltarget'=>$TDdrltarget,
        'MTDdrl'=>$MTDdrl,
        'MTDdrltarget'=>$MTDdrltarget,

        'TDdrr'=>$TDdrr,
        'TDdrrtarget'=>$TDdrrtarget,
        'MTDdrr'=>$MTDdrr,
        'MTDdrrtarget'=>$MTDdrrtarget,

        );
        return view('screenboard.index')->with($data);
    }

    public function screenboardindexReload(Request $request){
        $section = $request->input('section');
        $shifthrs = $request->input('shift');
        $today = carbon::today()->format('Y-m-d');
        $yesterday = carbon::yesterday()->format('Y-m-d');
        $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $firstthismonthY = Carbon::create($yesterday)->startOfMonth()->format('Y-m-d');

        $efshops = Shop::where('check_shop','=','1')->get(['report_name','id']);
         unset($efshops[9]);

        if($section == 'plant'){$sectionname = "PLANT";
        //PLANT SCREEN

        //Plant efficiency TODAY
        $tddrhrs = Attendance::where('date','=',$yesterday)->sum(DB::raw('efficiencyhrs'));
        $tdlnhrs = Attendance::where('date','=',$yesterday)->sum(DB::raw('loaned_hrs'));
        $tdotlnhrs = Attendance::where('date','=',$yesterday)->sum(DB::raw('otloaned_hrs'));
        $TDinput = $tddrhrs + $tdlnhrs + $tdotlnhrs;

        $TDoutput = Unitmovement::where('datetime_out','=',$yesterday)->sum('std_hrs');
        $TDplant_eff = ($TDinput > 0) ? round(($TDoutput/$TDinput)*100,0) : 0;

        //MTD EFFICIENCY
        $MTDplant_eff = getPlantEfficiency($firstthismonthY, $yesterday);//($MTDinput > 0) ? round(($MTDoutput/$MTDinput)*100,0) : 0;
        $planteff_target = round(getTarget($firstthismonthY)->efficiency,0);

        //Plant FCW today
        $TDfcw = Unitmovement::where([['datetime_out','=',$today],['shop_id','=',16]])->count();
        $TDfcwtarget = Production_target::where([['date','=',$today],['level','=','fcw']])->sum('noofunits');

        //Plant FCW MTD
        $MTDfcw = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',16)->count();
        $MTDfcwtarget = Production_target::whereBetween('date', [$firstthismonth, $today])->where('level','=','fcw')->sum('noofunits');

        //Plant Offline today
        $TDoffline = Unitmovement::where([['datetime_out','=',$today],['shop_id','=',8]])->count() + Unitmovement::where([['datetime_out','=',$today],['shop_id','=',10]])->count() + Unitmovement::where([['datetime_out','=',$today],['shop_id','=',13]])->count();
        $TDofflinetarget = Production_target::where([['date','=',$today],['level','=','offline']])->sum('noofunits');

        //Plant Offline MTD
        $MTDoffline = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',8)->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',10)->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',13)->count();
        $MTDofflinetarget = Production_target::whereBetween('date', [$firstthismonth, $today])->where('level','=','offline')->sum('noofunits');

        //GCA MTD
        $cvid = GcaScore::where('lcv_cv','=','cv')->max('id'); $lcvid = GcaScore::where('lcv_cv','=','lcv')->max('id');
        $cvwdpv = round(GcaScore::where('id','=',$cvid)->value('mtdwdpv'),2);
        $lcvwdpv = round(GcaScore::where('id','=',$lcvid)->value('mtdwdpv'),2);

        $cvdefects = GcaScore::where('id','=',$cvid)->sum(DB::raw('defectcar1 + defectcar1'));
        $lcvdefects = GcaScore::where('id','=',$lcvid)->sum(DB::raw('defectcar1 + defectcar1'));


        $cvdpv = round(($cvdefects/GcaScore::where('id','=',$cvid)->value('units_sampled')),2);
        $lcvdpv = round(($lcvdefects/GcaScore::where('id','=',$lcvid)->value('units_sampled')),2);


        $cvdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->cvdpv,1);
        $cvwdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->cvwdpv,1);
        $lcvdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->lcvdpv,1);
        $lcvwdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->lcvwdpv,1);

        //TEAMLEADER AVAILABILITY
        //Today
        $plantTThrs =0; $plantindirect = 0;
        foreach($efshops as $shop){
            $direct = 0; $indirect = 0;
            $teamleaders = Employee::where([['team_leader','=','yes'],['shop_id', '=', $shop->id],['status','=','Active']])->get('id');
            foreach($teamleaders as $tl){
                $direct += Attendance::where([['staff_id','=',$tl->id],['date', '=', $yesterday]])
                            ->sum(DB::raw('direct_hrs + othours'));
                $indirect += Attendance::where([['staff_id','=',$tl->id],['date', '=', $yesterday]])
                            ->sum(DB::raw('indirect_hrs + indirect_othours'));
            }
            $plantTThrs += $indirect+$direct;
            $plantindirect += $indirect;
        }

        $TDTLavail = ($plantTThrs > 0) ? round(($plantindirect/$plantTThrs)*100,0) : 0;

        //MTD T/L Availability
        $plantTThrs =0; $plantdirect = 0;
        foreach($efshops as $shop){
            $teamleaders = Employee::where([['team_leader','=','yes'],['shop_id', '=', $shop->id],['status','=','Active']])->get('id');
            foreach($teamleaders as $tl){
                $direct = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])
                        ->where('staff_id','=',$tl->id)->sum(DB::raw('direct_hrs + othours'));
                $indirect = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])
                        ->where('staff_id','=',$tl->id)->sum(DB::raw('indirect_hrs + indirect_othours'));
                $plantTThrs += $direct + $indirect;
                $plantdirect += $indirect;
            }
        }
        $MTDTLavail = ($plantTThrs == 0) ? 0 : round(($plantdirect/$plantTThrs)*100,2);
        $plantTL_target = round(getplantTLAtarget(),0);

        //ABSENTEEISM
        //Yesterday absenteeism
        $empcount = Attendance::where('date','=',$yesterday)->count();
        //if($empcount != null){
            $expectedhrs = $empcount * 8;
            $hrsworked = Attendance::where('date','=',$yesterday)
                            ->sum(DB::raw('direct_hrs + indirect_hrs'));
            $absent = $expectedhrs - $hrsworked;
            ($absent > 0) ? $TDabsentiesm = round(((($absent)/$expectedhrs)*100),0) : $TDabsentiesm = 0;
        //}

        //MTD absenteeism
        $empcount = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->count();
        if($empcount != null){
            $expectedhrs = $empcount * 8;
            $hrsworked = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])
                            ->sum(DB::raw('direct_hrs + indirect_hrs'));
            $absent = $expectedhrs - $hrsworked;
            ($absent > 0) ? $MTDabsentiesm = round(((($absent)/$expectedhrs)*100),0) : $MTDabsentiesm = 0;
        }
        $plantAB_target = round(getTarget($firstthismonthY)->absentieesm,0);

        //DRL & DRR
        $TDdrl = drl_today()['drl'];
        $TDdrltarget = drl_today()['drl_target_value'];
        $MTDdrl = month_to_date_drl()['drl'];
        $MTDdrltarget = month_to_date_drl()['drl_target_value'];

        $TDdrr = today_drr()['plant_drr'];
        $TDdrrtarget = today_drr()['drr_target_value'];
        $MTDdrr = month_to_date_drr()['plant_drr'];
        $MTDdrrtarget = month_to_date_drr()['drr_target_value'];

    }


    elseif($section == 'cv' || $section == 'lcv'){
        //CV & LCV SCREEN
        if($section == 'cv'){
            $sectionname = "CV";
            $shops = Shop::where('lcvcv_share','cv')->orwhere('lcvcv_share','share')->get(['id','shop_name']);
            unset($shops[3]);
            unset($shops[6]);
            unset($shops[8]);
        }else{
            $sectionname = "LCV";
             $shops = Shop::where('lcvcv_share','lcv')->get(['id','shop_name']);
             unset($shops[1]);
        }

        $ttinputhrs = 0; $ttoutputhrs = 0; $MTDinputhrs = 0; $MTDoutputhrs = 0; $MTDttindirect = 0; $TDttindirect = 0;
        $TDdirect = 0; $TDindirect = 0; $TDtthrs = 0; $MTDdirect = 0; $MTDindirect = 0; $MTDtthrs = 0;
        $TDempcount = 0; $MTDempcount = 0; $TDhrsworked = 0; $MTDhrsworked = 0;
        foreach($shops as $sp){
            //Plant efficiency TODAY
            $efftthrs = Attendance::where([['date',$yesterday],['shop_id',$sp->id]])->sum(DB::raw('efficiencyhrs'));
            $spmtdlnhrs = Attendance::where([['date',$yesterday],['shop_loaned_to',$sp->id]])->sum(DB::raw('loaned_hrs'));
            $spmtdotlnhrs = Attendance::where([['date',$yesterday],['shop_loaned_to',$sp->id]])->sum(DB::raw('otloaned_hrs'));
            $suminputhrs = $efftthrs + $spmtdlnhrs + $spmtdotlnhrs;
            $ttinputhrs += $suminputhrs;

            $ttoutputhrs += Unitmovement::where([['datetime_out',$yesterday],['shop_id',$sp->id]])->sum('std_hrs');

            //Plant efficiency MTD
            $MTDinputhrs += Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$sp->id)->sum(DB::raw('efficiencyhrs + loaned_hrs + otloaned_hrs'));
            $MTDoutputhrs += Unitmovement::whereBetween('datetime_out', [$firstthismonthY, $yesterday])->where('shop_id',$sp->id)->sum('std_hrs');

            //TEAMLEADER AVAILABILITY
            $teamleaders = Employee::where([['team_leader','=','yes'],['status','=','Active'],['shop_id',$sp->id]])->get('id');
            foreach($teamleaders as $tl){
                //Today
                $TDdirect = Attendance::where([['staff_id','=',$tl->id],['date', '=', $yesterday]])
                            ->sum(DB::raw('direct_hrs + othours'));
                $TDindirect = Attendance::where([['staff_id','=',$tl->id],['date', '=', $yesterday]])
                            ->sum(DB::raw('indirect_hrs + indirect_othours'));
                $TDtthrs += $TDdirect + $TDindirect;
                $TDttindirect += $TDindirect;

                //MTD
                $MTDdirect = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('staff_id','=',$tl->id)
                        ->sum(DB::raw('direct_hrs + othours'));
                $MTDindirect = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('staff_id','=',$tl->id)
                        ->sum(DB::raw('indirect_hrs + indirect_othours'));
                $MTDtthrs += $MTDdirect + $MTDindirect;
                $MTDttindirect += $MTDindirect;
            }

            //ABSENTEEISM
            //Yesterday absenteeism
            $TDempcount += Attendance::where([['date','=',$yesterday],['shop_id',$sp->id]])->count();
            $TDhrsworked += Attendance::where([['date','=',$yesterday],['shop_id',$sp->id]])->sum(DB::raw('direct_hrs + indirect_hrs'));
            //MTD absenteeism
            $MTDempcount += Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$sp->id)->count();
            $MTDhrsworked += Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$sp->id)->sum(DB::raw('direct_hrs + indirect_hrs'));
        }

        $TDTLavail = ($TDtthrs > 0) ? round(($TDttindirect/$TDtthrs)*100,0) : 0;

        $MTDTLavail = ($MTDtthrs > 0) ? round(($MTDttindirect/$MTDtthrs)*100,0) : 0;
        $plantTL_target = round(getplantTLAtarget(),0);

        $TDexpectedhrs = $TDempcount * 8;    $TDabsent = $TDexpectedhrs - $TDhrsworked;
        ($TDabsent > 0) ? $TDabsentiesm = round(((($TDabsent)/$TDexpectedhrs)*100),0) : $TDabsentiesm = 0;

        $MTDexpectedhrs = $MTDempcount * 8;     $MTDabsent = $MTDexpectedhrs - $MTDhrsworked;
        ($MTDabsent > 0) ? $MTDabsentiesm = round(((($MTDabsent)/$MTDexpectedhrs)*100),0) : $MTDabsentiesm = 0;
        $plantAB_target = round(getTarget($firstthismonthY)->absentieesm,0);

        $TDplant_eff = ($ttinputhrs > 0) ? round(($ttoutputhrs/$ttinputhrs)*100,0) : 0;
        $MTDplant_eff = ($MTDinputhrs > 0) ? round(($MTDoutputhrs/$MTDinputhrs)*100,0) : 0;
        $planteff_target = round(getTarget($firstthismonthY)->efficiency,0);

        //FCW MTD and today
        if($section == 'cv'){
            //FCW
            $TDfcw = Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',1]])->count() + Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',2]])->count() + Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',3]])->count();
            $MTDfcw = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',1]])->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',2]])->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',3]])->count();
            $TDfcwtarget = Production_target::where([['cv','=',$today],['level','=','fcw']])->sum('noofunits');
            $MTDfcwtarget = Production_target::whereBetween('cv', [$firstthismonth, $today])->where('level','=','fcw')->sum('noofunits');

            //OFFLINE
            $TDoffline = Unitmovement::where([['datetime_out','=',$today],['shop_id','=',8]])->count() + Unitmovement::where([['datetime_out','=',$today],['shop_id','=',10]])->count();
            $TDofflinetarget = Production_target::where([['date','=',$today],['level','=','offline'],['route_id',1]])
                    ->orWhere(function ($query) use ($today) {
                        $query->where([['date','=',$today],['level','=','offline'],['route_id',3]]);
                        })->orWhere(function ($query) use ($today) {
                        $query->where([['date','=',$today],['level','=','offline'],['route_id',5]]);
                        })->sum('noofunits');

			//Production_target::where([['date','=',$today],['level','=','offline'],['route_id',1]])->sum('noofunits');
            $MTDoffline = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',8)->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',10)->count();
            $MTDofflinetarget  = Production_target::whereBetween('date',[$firstthismonth, $today])->where([['level','=','offline'],['route_id',1]])
                    ->orWhere(function ($query) use ($firstthismonth, $today) {
                        $query->where([['level','=','offline'],['route_id',3]])
                            ->whereBetween('date',[$firstthismonth, $today]);
                        })->orWhere(function ($query) use ($firstthismonth, $today) {
                        $query->where([['level','=','offline'],['route_id',5]])
                            ->whereBetween('date',[$firstthismonth, $today]);
                        })->sum('noofunits');
            //CV GCA
            $cvid = GcaScore::where('lcv_cv','=','cv')->max('id');
            $cvwdpv = round(GcaScore::where('id','=',$cvid)->value('mtdwdpv'),2);
            $cvdefects = GcaScore::where('id','=',$cvid)->sum(DB::raw('defectcar1 + defectcar1'));
            $cvdpv = round(($cvdefects/GcaScore::where('id','=',$cvid)->value('units_sampled')),2);
            $maxid = GcaTarget::max('id');
            $cvdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->cvdpv,1);
            $cvwdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->cvwdpv,1);
            $lcvdpv = 0; $lcvdpvtarget = 0; $lcvwdpv = 0; $lcvwdpvtarget = 0;

            //DRL & DRR
            $TDdrl = drl_today()['drl'];
            $TDdrltarget = drl_today()['drl_target_value'];
            $MTDdrl = month_to_date_drl()['drl'];
            $MTDdrltarget = month_to_date_drl()['drl_target_value'];

            $TDdrr = today_drr()['plant_drr'];
            $TDdrrtarget = today_drr()['drr_target_value'];
            $MTDdrr = month_to_date_drr()['plant_drr'];
            $MTDdrrtarget = month_to_date_drr()['drr_target_value'];
        }else{
            //FCW
            $TDfcw = Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',5]])->count() + Unitmovement::where([['datetime_out',$today],['shop_id',16],['route_number',4]])->count();
            $MTDfcw = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',4]])->count() + Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where([['shop_id',16],['route_number',5]])->count();
            $TDfcwtarget = Production_target::where([['lcv','=',$today],['level','=','fcw']])->sum('noofunits');
            $MTDfcwtarget = Production_target::whereBetween('lcv', [$firstthismonth, $today])->where('level','=','fcw')->sum('noofunits');

            //OFFLINE
            $TDoffline = Unitmovement::where([['datetime_out','=',$today],['shop_id','=',13]])->count();
            $TDofflinetarget = Production_target::where([['date','=',$today],['level','=','offline'],['route_id',7]])
                    ->orWhere(function ($query) use ($today) {
                        $query->where([['date','=',$today],['level','=','offline'],['route_id',9]]);
                        })->sum('noofunits');

			//Production_target::where([['lcv','=',$today],['level','=','offline']])->sum('noofunits');
            $MTDoffline = Unitmovement::whereBetween('datetime_out', [$firstthismonth, $today])->where('shop_id','=',13)->count();

            $MTDofflinetarget  = Production_target::whereBetween('date',[$firstthismonth, $today])->where([['level','=','offline'],['route_id',7]])
                    ->orWhere(function ($query) use ($firstthismonth, $today) {
                        $query->where([['level','=','offline'],['route_id',9]])
                            ->whereBetween('date',[$firstthismonth, $today]);
                        })->sum('noofunits');


            //LCV GCA
            $lcvid = GcaScore::where('lcv_cv','=','lcv')->max('id');
            $lcvwdpv = round(GcaScore::where('id','=',$lcvid)->value('mtdwdpv'),2);
            $lcvdefects = GcaScore::where('id','=',$lcvid)->sum(DB::raw('defectcar1 + defectcar1'));
            $lcvdpv = round(($lcvdefects/GcaScore::where('id','=',$lcvid)->value('units_sampled')),2);


            $lcvdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->lcvdpv,1);
            $lcvwdpvtarget = (getGCATarget($today) == '0')? 0 : round(getGCATarget($today)->lcvwdpv,1);
            $cvdpv = 0; $cvdpvtarget = 0; $cvwdpv = 0; $cvwdpvtarget = 0;

            //DRL & DRR
            $TDdrl = drl_today()['drl'];
            $TDdrltarget = drl_today()['drl_target_value'];
            $MTDdrl = month_to_date_drl()['drl'];
            $MTDdrltarget = month_to_date_drl()['drl_target_value'];

            $TDdrr = today_drr()['plant_drr'];
            $TDdrrtarget = today_drr()['drr_target_value'];
            $MTDdrr = month_to_date_drr()['plant_drr'];
            $MTDdrrtarget = month_to_date_drr()['drr_target_value'];
        }

 }

    $data = array(

        'time' => Carbon::now()->format('g:i:s A'),
        'shifthrs'=>$shifthrs,
        'sectionname'=>$sectionname,

        'TDabsentiesm'=>$TDabsentiesm,
        'MTDabsentiesm'=>$MTDabsentiesm,
        'plantAB_target'=>$plantAB_target,

        'TDTLavail'=>$TDTLavail,
        'MTDTLavail'=>$MTDTLavail,
        'plantTL_target'=>$plantTL_target,

        'cvdpv'=>$cvdpv,
        'lcvdpv'=>$lcvdpv,
        'cvwdpv'=>$cvwdpv,
        'lcvwdpv'=>$lcvwdpv,

        'cvdpvtarget'=>round($cvdpvtarget,1),
        'cvwdpvtarget'=>round($cvwdpvtarget,1),
        'lcvdpvtarget'=>round($lcvdpvtarget,1),
        'lcvwdpvtarget'=>round($lcvwdpvtarget,1),

        'MTDoffline'=>$MTDoffline,
        'TDoffline'=>$TDoffline,
        'MTDofflinetarget'=>$MTDofflinetarget,
        'TDofflinetarget'=>$TDofflinetarget,

        'MTDfcw'=>$MTDfcw,
        'TDfcw'=>$TDfcw,
        'MTDfcwtarget'=>$MTDfcwtarget,
        'TDfcwtarget'=>$TDfcwtarget,

        'TDplant_eff'=>$TDplant_eff,
        'MTDplant_eff'=>$MTDplant_eff,
        'planteff_target'=>$planteff_target,

        'TDdrl'=>$TDdrl,
        'TDdrltarget'=>$TDdrltarget,
        'MTDdrl'=>$MTDdrl,
        'MTDdrltarget'=>$MTDdrltarget,

        'TDdrr'=>$TDdrr,
        'TDdrrtarget'=>$TDdrrtarget,
        'MTDdrr'=>$MTDdrr,
        'MTDdrrtarget'=>$MTDdrrtarget,

        );
        return response()->json(['data' => $data], 200);
    }


    public function screenboardpershop(Request $request){
        $shopid = $request->input('section');
        $shifthrs = $request->input('shift');
        $date = Carbon::today()->format('Y-m-d');


        //DAILY REALTIME PRODUCTION - taget
        if($shopid == 8){
            $units = Production_target::where([['shop'.$shopid.'','=',$date],['route_id','=',1]])->value('noofunits');
        }elseif($shopid == 10){
            $units = Production_target::where([['shop'.$shopid.'','=',$date],['route_id','=',3]])->value('noofunits');
        }elseif($shopid == 14 || $shopid == 15 || $shopid == 16){
            $cv = Production_target::where('cv','=',$date)->value('noofunits');
             $lcv = Production_target::where('lcv','=',$date)->value('noofunits');
            $units = $cv + $lcv;
        }else{
            $units = Production_target::where('shop'.$shopid.'','=',$date)->sum('noofunits');
        }
        $units = ($units == "") ? 0 : $units;

        $now = Carbon::now()->format('Y-m-d H:i');
        $starttime = date('Y-m-d H:i', strtotime($date.' 07:20'));
        $endtime = ($shifthrs == 11) ? date('Y-m-d H:i', strtotime($date.' 18:00')) : date('Y-m-d H:i', strtotime($date.' 16:00'));
        $startbreaktime = date('Y-m-d H:i', strtotime($date.' 09:30'));
        $endbreaktime = date('Y-m-d H:i', strtotime($date.' 09:45'));
        $startlunchtime  = date('Y-m-d H:i', strtotime($date.' 12:30'));
        $endlunchtime  = date('Y-m-d H:i', strtotime($date.' 13:00'));

        $dteStart = Carbon::parse($starttime);
        $dteEnd   = Carbon::parse($now);

        if($now > $startbreaktime && $now <= $endbreaktime){
            $dteEnd   = Carbon::parse(date('Y-m-d H:i', strtotime($date.' 09:30')));
        }
        if($now > $startlunchtime && $now <= $endlunchtime){
            $dteEnd   = Carbon::parse(date('Y-m-d H:i', strtotime($date.' 12:30')));
        }



        $interval = $dteEnd->diffInSeconds($dteStart);
        $tthrs = $shifthrs;
        if($now > $endbreaktime && $now < $endlunchtime){
            $interval = $interval - 900;
            $tthrs = $shifthrs - 0.25;
        }
        if($now > $endlunchtime){
            $interval = $interval - 1800;
            $tthrs = $shifthrs - 0.75;
        }

        $hours = $interval/3600;

        $realtimeTarget = floor(($hours/$tthrs)*$units);

        //No of units - actual
        $completedunits = Unitmovement::where([['shop_id','=',$shopid],['datetime_out','=',$date]])->count();

        if($now < $starttime){
            $t_actual = 0;
            $t_target = $units;
        }elseif($now >= $starttime && $now <= $endtime){
            $t_actual = $completedunits;
            $t_target = $realtimeTarget;
        }elseif($now > $endtime){
            $t_actual = $completedunits;
            $t_target = $units;
        }

        //check if schedule exist
        $today = carbon::today()->format('Y-m-d');
        $shopname = Shop::where('id','=',$shopid)->value('shop_name');
        $shopid1 = ($shopid=='14') ? 16 : $shopid; $shopid1 = ($shopid1=='15')?16:$shopid1;

       $existchedule = Production_target::whereDate('shop'.$shopid1.'',$today)->first();
        if($existchedule == ""){
            //no schedule
            $lastday = Production_target::max('shop'.$shopid1.'');
            $fisrtday = Carbon::parse($lastday)->addDays(1)->format('Y-m-d');

            $balanceBF = broughtfoward($shopid1,$today);
            $units = UnitMovement::whereBetween('datetime_out',[$fisrtday,$today])->where('shop_id',$shopid1)->count();
            $actualunits = $balanceBF + $units;
            $mtdtarget = 0;
        }else{
            //schedule exist
            $fisrtday = getProductionstartdate($shopid1,$existchedule->date);
            $balanceBF = broughtfoward($shopid1,$today);
            $units = UnitMovement::whereBetween('datetime_out',[$fisrtday,$today])->where('shop_id',$shopid1)->count();
            $actualunits = $balanceBF + $units;

            $lastday1 = getProductionenddate($shopid1,$existchedule->date);
            $fisrtday1 = Carbon::parse($lastday1)->addDays(1)->format('Y-m-d');
            if(carbon::parse($fisrtday1) == carbon::parse($today)){
                $yesterday = carbon::today()->format('Y-m-d');
            }else{
                $yesterday = carbon::yesterday()->format('Y-m-d');
            }

            if($shopid1 == 8){
                $mtdtarget = Production_target::whereBetween('shop'.$shopid1.'',[$fisrtday,$yesterday])
                                ->where('route_id',1)->sum('noofunits');
            }elseif($shopid1 == 10){
                $mtdtarget = $mtdtarget = Production_target::whereBetween('shop'.$shopid1.'',[$fisrtday,$yesterday])
                ->where('route_id',3)->sum('noofunits');
            }elseif($shopid1 == 14 || $shopid1 == 15 || $shopid1 == 16){
                $firstthismonth = carbon::now()->startOfMonth()->format('Y-m-d');
                $cv = Production_target::whereBetween('cv', [$firstthismonth, $yesterday])->sum('noofunits');
                $lcv = Production_target::whereBetween('lcv', [$firstthismonth, $yesterday])->sum('noofunits');
                $mtdtarget = $cv + $lcv;
            }else{
                $mtdtarget = Production_target::whereBetween('shop'.$shopid1.'',[$fisrtday,$yesterday])->sum('noofunits');
            }

        }



        //actual
        $MTDcompletedunits = Unitmovement::where('shop_id','=',$shopid)
                        ->whereBetween('datetime_out', [getProductionstartdate($shopid,$today), $today])->count();
        $today = carbon::today()->format('Y-m-d');
        $yesterday = carbon::yesterday()->format('Y-m-d');
        $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $firstthismonthY = Carbon::create($yesterday)->startOfMonth()->format('Y-m-d');

        //EFFICIENCY
        $MTDshop_eff = getshopEfficiency($firstthismonthY, $yesterday,$shopid1);//($MTDinput > 0) ? round(($MTDoutput/$MTDinput)*100,0) : 0;

        $MTDeff_target = round(getTarget($firstthismonthY)->efficiency,0);


        $data = array(
            'time' => Carbon::now()->format('g:i:s A'),
            'shifthrs'=>$shifthrs,
            'shopname'=>$shopname,
            'actual'=>$t_actual,
            'target'=>$t_target,

            'MTDactualunits'=>$MTDcompletedunits,
            'mtdtarget'=>$mtdtarget + $t_target,

            'MTDshop_eff'=>$MTDshop_eff,
            'MTDeff_target'=>$MTDeff_target,

            'TDdrlactual'=>drl_per_shop_today($shopid)['drl'],
            'MTDdrlactual'=>month_to_date_drl_per_shop($shopid)['drl'],
            'TDdrltarget'=>drl_per_shop_today($shopid)['drl_target_value'],
            'MTDdrltarget'=>month_to_date_drl_per_shop($shopid)['drl_target_value'],
            'shopid'=> $shopid1,
        );
        return view('screenboard.pershop')->with($data);
    }



    public function screenboardpershopReload(Request $request){
        $shopid = $request->input('section');
        $shifthrs = $request->input('shift');
        $date = Carbon::today()->format('Y-m-d');


        //DAILY REALTIME PRODUCTION - taget
        if($shopid == 8){
            $units = Production_target::where([['shop'.$shopid.'','=',$date],['route_id','=',1]])->value('noofunits');
        }elseif($shopid == 10){
            $units = Production_target::where([['shop'.$shopid.'','=',$date],['route_id','=',3]])->value('noofunits');
        }elseif($shopid == 14 || $shopid == 15 || $shopid == 16){
            $cv = Production_target::where('cv','=',$date)->value('noofunits');
             $lcv = Production_target::where('lcv','=',$date)->value('noofunits');
            $units = $cv + $lcv;
        }else{
            $units = Production_target::where('shop'.$shopid.'','=',$date)->sum('noofunits');
        }
        $units = ($units == "") ? 0 : $units;

        $now = Carbon::now()->format('Y-m-d H:i');
        $starttime = date('Y-m-d H:i', strtotime($date.' 07:20'));
        $endtime = ($shifthrs == 11) ? date('Y-m-d H:i', strtotime($date.' 18:00')) : date('Y-m-d H:i', strtotime($date.' 16:00'));
        $startbreaktime = date('Y-m-d H:i', strtotime($date.' 09:30'));
        $endbreaktime = date('Y-m-d H:i', strtotime($date.' 09:45'));
        $startlunchtime  = date('Y-m-d H:i', strtotime($date.' 12:30'));
        $endlunchtime  = date('Y-m-d H:i', strtotime($date.' 13:00'));

        $dteStart = Carbon::parse($starttime);
        $dteEnd   = Carbon::parse($now);

        if($now > $startbreaktime && $now <= $endbreaktime){
            $dteEnd   = Carbon::parse(date('Y-m-d H:i', strtotime($date.' 09:30')));
        }
        if($now > $startlunchtime && $now <= $endlunchtime){
            $dteEnd   = Carbon::parse(date('Y-m-d H:i', strtotime($date.' 12:30')));
        }



        $interval = $dteEnd->diffInSeconds($dteStart);
        $tthrs = $shifthrs;
        if($now > $endbreaktime && $now < $endlunchtime){
            $interval = $interval - 900;
            $tthrs = $shifthrs - 0.25;
        }
        if($now > $endlunchtime){
            $interval = $interval - 1800;
            $tthrs = $shifthrs - 0.75;
        }

        $hours = $interval/3600;

        $realtimeTarget = floor(($hours/$tthrs)*$units);

        //No of units - actual
        $completedunits = Unitmovement::where([['shop_id','=',$shopid],['datetime_out','=',$date]])->count();

        if($now < $starttime){
            $t_actual = 0;
            $t_target = $units;
        }elseif($now >= $starttime && $now <= $endtime){
            $t_actual = $completedunits;
            $t_target = $realtimeTarget;
        }elseif($now > $endtime){
            $t_actual = $completedunits;
            $t_target = $units;
        }

        //check if schedule exist
        $today = carbon::today()->format('Y-m-d');
        $shopname = Shop::where('id','=',$shopid)->value('shop_name');
        $shopid1 = ($shopid=='14') ? 16 : $shopid; $shopid1 = ($shopid1=='15')?16:$shopid1;

       $existchedule = Production_target::whereDate('shop'.$shopid1.'',$today)->first();
        if($existchedule == ""){
            //no schedule
            $lastday = Production_target::max('shop'.$shopid1.'');
            $fisrtday = Carbon::parse($lastday)->addDays(1)->format('Y-m-d');

            $balanceBF = broughtfoward($shopid1,$today);
            $units = UnitMovement::whereBetween('datetime_out',[$fisrtday,$today])->where('shop_id',$shopid1)->count();
            $actualunits = $balanceBF + $units;
            $mtdtarget = 0;
        }else{
            //schedule exist
            $fisrtday = getProductionstartdate($shopid1,$existchedule->date);
            $balanceBF = broughtfoward($shopid1,$today);
            $units = UnitMovement::whereBetween('datetime_out',[$fisrtday,$today])->where('shop_id',$shopid1)->count();
            $actualunits = $balanceBF + $units;

            $lastday1 = getProductionenddate($shopid1,$existchedule->date);
            $fisrtday1 = Carbon::parse($lastday1)->addDays(1)->format('Y-m-d');
            if(carbon::parse($fisrtday1) == carbon::parse($today)){
                $yesterday = carbon::today()->format('Y-m-d');
            }else{
                $yesterday = carbon::yesterday()->format('Y-m-d');
            }

            if($shopid1 == 8){
                $mtdtarget = Production_target::whereBetween('shop'.$shopid1.'',[$fisrtday,$yesterday])
                                ->where('route_id',1)->sum('noofunits');
            }elseif($shopid1 == 10){
                $mtdtarget = $mtdtarget = Production_target::whereBetween('shop'.$shopid1.'',[$fisrtday,$yesterday])
                ->where('route_id',3)->sum('noofunits');
            }elseif($shopid1 == 14 || $shopid1 == 15 || $shopid1 == 16){
                $firstthismonth = carbon::now()->startOfMonth()->format('Y-m-d');
                $cv = Production_target::whereBetween('cv', [$firstthismonth, $yesterday])->sum('noofunits');
                $lcv = Production_target::whereBetween('lcv', [$firstthismonth, $yesterday])->sum('noofunits');
                $mtdtarget = $cv + $lcv;
            }else{
                $mtdtarget = Production_target::whereBetween('shop'.$shopid1.'',[$fisrtday,$yesterday])->sum('noofunits');
            }

        }

        //actual
        $MTDcompletedunits = Unitmovement::where('shop_id','=',$shopid)
                ->whereBetween('datetime_out', [getProductionstartdate($shopid,$today), $today])->count();
        $today = carbon::today()->format('Y-m-d');
        $yesterday = carbon::yesterday()->format('Y-m-d');
        $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $firstthismonthY = Carbon::create($yesterday)->startOfMonth()->format('Y-m-d');

        //EFFICIENCY
        $MTDshop_eff = getshopEfficiency($firstthismonthY, $yesterday,$shopid1);//($MTDinput > 0) ? round(($MTDoutput/$MTDinput)*100,0) : 0;

        $MTDeff_target = round(getTarget($firstthismonthY)->efficiency,0);

        $data = array(
            'time' => Carbon::now()->format('g:i:s A'),
            'shifthrs'=>$shifthrs,
            'shopname'=>$shopname,
            'actual'=>$t_actual,
            'target'=>$t_target,

            'MTDactualunits'=>$MTDcompletedunits,
            'mtdtarget'=>$mtdtarget + $t_target,

            'MTDshop_eff'=>$MTDshop_eff,
            'MTDeff_target'=>$MTDeff_target,

            'TDdrlactual'=>drl_per_shop_today($shopid)['drl'],
            'MTDdrlactual'=>month_to_date_drl_per_shop($shopid)['drl'],
            'TDdrltarget'=>drl_per_shop_today($shopid)['drl_target_value'],
            'MTDdrltarget'=>month_to_date_drl_per_shop($shopid)['drl_target_value'],
            'shopid'=> $shopid,
        );
        return response()->json(['data' => $data], 200);
    }

    public function screenboarddefects($shopid){
        $units_array = Unitmovement::where('current_shop','>',0)->where('shop_id',$shopid)->pluck('vehicle_id');

        $units = Querydefect::groupBy('vehicle_id')->whereIn('vehicle_id',$units_array)->get();

        $shopname = Shop::where('id','=',$shopid)->value('shop_name');
        $data = array(
            'time' => Carbon::now()->format('g:i:s A'),
            'units'=>$units,
            'shopname'=>$shopname,
            'shopid'=>$shopid,
        );



        return view('screenboard.defects')->with($data);
    }
    public function screenboardall (Request $request){

        $shops = Shop::where('check_point','=',1)->orderby('shop_no')->get(['id','shop_name']);
        unset($shops[7]); //Remove inline F-Series
        unset($shops[5]); //Remove inline N-Series
        unset($shops[12]); //Remove inline N-Series


            $shifthrs = $request->input('shift');
            $date = Carbon::today()->format('Y-m-d');


        foreach($shops as $sp){ $shopid = $sp->id;

            //DAILY REALTIME PRODUCTION - taget
            if($shopid == 8){
                $units = Production_target::where([['shop'.$shopid.'','=',$date],['route_id','=',1]])->value('noofunits');
            }elseif($shopid == 10){
                $units = Production_target::where([['shop'.$shopid.'','=',$date],['route_id','=',3]])->value('noofunits');
            }elseif($shopid == 11 || $shopid == 12 || $shopid == 13){
                $units = Production_target::where('shop'.$shopid.'','=',$date)->value('noofunits');
            }elseif($shopid == 14 || $shopid == 15 || $shopid == 16){
                $cv = Production_target::where('cv','=',$date)->value('noofunits');
                $lcv = Production_target::where('lcv','=',$date)->value('noofunits');
                $units = $cv + $lcv;
            }else{
                $units = Production_target::where('shop'.$shopid.'','=',$date)->sum('noofunits');
            }
            $units = ($units == "") ? 0 : $units;

            $now = Carbon::now()->format('Y-m-d H:i');
            $starttime = date('Y-m-d H:i', strtotime($date.' 07:20'));
            $endtime = ($shifthrs == 11) ? date('Y-m-d H:i', strtotime($date.' 18:00')) : date('Y-m-d H:i', strtotime($date.' 16:00'));
            $startbreaktime = date('Y-m-d H:i', strtotime($date.' 09:30'));
            $endbreaktime = date('Y-m-d H:i', strtotime($date.' 09:45'));
            $startlunchtime  = date('Y-m-d H:i', strtotime($date.' 12:30'));
            $endlunchtime  = date('Y-m-d H:i', strtotime($date.' 13:00'));

            $dteStart = Carbon::parse($starttime);
            $dteEnd   = Carbon::parse($now);

            if($now > $startbreaktime && $now <= $endbreaktime){
                $dteEnd   = Carbon::parse(date('Y-m-d H:i', strtotime($date.' 09:30')));
            }
            if($now > $startlunchtime && $now <= $endlunchtime){
                $dteEnd   = Carbon::parse(date('Y-m-d H:i', strtotime($date.' 12:30')));
            }



            $interval = $dteEnd->diffInSeconds($dteStart);
            $tthrs = $shifthrs;
            if($now > $endbreaktime && $now < $endlunchtime){
                $interval = $interval - 900;
                $tthrs = $shifthrs - 0.25;
            }
            if($now > $endlunchtime){
                $interval = $interval - 1800;
                $tthrs = $shifthrs - 0.75;
            }

            $hours = $interval/3600;

            $realtimeTarget = floor(($hours/$tthrs)*$units);

            //No of units - actual
            $completedunits = Unitmovement::where([['shop_id','=',$shopid],['datetime_out','=',$date]])->count();

            if($now < $starttime){
                $t_actual = 0;
                $t_target = $units;
            }elseif($now >= $starttime && $now <= $endtime){
                $t_actual = $completedunits;
                $t_target = $realtimeTarget;
            }elseif($now > $endtime){
                $t_actual = $completedunits;
                $t_target = $units;
            }

            $finishedunits[$shopid] = $t_actual;
            $targetunits[$shopid] = $t_target;
        }


        $data = array(
            'shops'=>$shops,
            'finishedunits'=>$finishedunits,
            'targetunits'=>$targetunits,
            'shift'=>($shifthrs == 9) ? 8 : 10,
        );
        return view('screenboard.allsections')->with($data);
    }

   public  function load_defects(Request $request){


        $vehicle_data=vehicle_units::find($request->vehicle_id);


        $data = array(
            'vehicle_data'=>$vehicle_data,
            'model_name'=>$vehicle_data->model->model_name,
            'vehicle_id'=>$request->vehicle_id,
        );



        return response()->json(['data' => $data], 200);




    }

public function load_datable_defects (Request $request){

    if (request()->ajax()) {
       // 'vehicle_id',$request->vehicle_id
     // 'shop_id',$request->shop_id

        $defects = Querydefect::where('vehicle_id',$request->vehicle_id)->get();

    return DataTables::of($defects)



                ->addColumn('routing_query', function ($defects) {
                    return $defects->getqueries->query_name;
                })

                ->addColumn('captured_shop', function ($defects) {
                    return $defects->qshops->shop_name;
                })

                ->addColumn('captured_by', function ($defects) {
                    return $defects->getqueryanswer->doneby->name;
                })

                ->addColumn('corrected_by', function ($defects) {
                    return $defects->defect_corrected_by;
                })
                ->addColumn('verified_by', function ($defects) {
                    $verified="";

                    if($defects->repaired=="Yes"){
                        $verified=$defects->corrected->name;

                    }
                    return $verified;
                })



                        ->addColumn('status', function ($defects) {
                            $status='<span class="badge badge-danger px-2 py-1">Not Corrected</span>';

                            if($defects->repaired=='Yes'){
                                $status='<span class="badge badge-primary px-2 py-1">Corrected</span>';

                            }
                        return  $status;
                    })->rawColumns(['status'])

                ->make(true);



}
}



}
