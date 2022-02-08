<?php

namespace App\Http\Controllers\graph;
use App\Http\Controllers\Controller;

use App\Models\shop\Shop;
use App\Models\employee\Employee;
use App\Models\attendance\Attendance;
use App\Models\std_working_hr\Std_working_hr;
use App\Models\unitmovement\Unitmovement;
use App\Models\indivtarget\IndivTarget;
use App\Models\vehicle_units\vehicle_units;
use App\Models\productiontarget\Production_target;
use App\Models\systemsettings\SystemSetting;

use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;

use Illuminate\Http\Request;



class GraphController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:Effny-dashboard', ['only' => ['plantefficiency']]);
    }


    public function plantefficiency(){

        $today = Carbon::yesterday()->format('Y-m-d');
        $first = Carbon::create($today)->startOfMonth()->format('Y-m-d');
        $firstthisyr = Carbon::create($today)->startOfYear()->format('Y-m-d');

        $YRschdates = Production_target::whereBetween('date', [$firstthisyr, $today])
                                ->groupby('date')->get(['date']);
        //vehicle_units::whereBetween('offline_date',[$firstthisyr,$today])->groupby('offline_date')->get(['offline_date']);

        foreach($YRschdates as $schdt){  $YRprodndays[] = $schdt->offline_date; }

        $MTschdates = Production_target::whereBetween('date', [$first, $today])
                                    ->groupby('date')->get(['date']);
        //vehicle_units::whereBetween('offline_date',[$first,$today])->groupby('offline_date')->get(['offline_date']);
        foreach($MTschdates as $schdt){  $MTprodndays[] = $schdt->offline_date; }

        $today = $MTprodndays[count($MTprodndays)-1];
        $first = $MTprodndays[0];
        $firstthisyr = $YRprodndays[0];
        $fromdate = $today;
        $todate = $today;

        // *******************   GRAPH CALCULATIONS    **********************
        $today = carbon::today()->format('Y-m-d');
        $yesterday = carbon::yesterday()->format('Y-m-d');
        $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $firstthismonthY = Carbon::create($yesterday)->startOfMonth()->format('Y-m-d');
        $firstthisyrY = Carbon::create($yesterday)->startOfYear()->format('Y-m-d');


        //----- shops for Efficiency and T/L Availability ------------
        $efshops = Shop::where('check_shop','=','1')->get(['report_name','id']);

         //----- shops for Absenteeism ------------
        $otshops = Shop::where('overtime','=','1')->get(['report_name','id']);

        //***********Shop names************* */
        $shopnames = []; $shops = []; $shopcolor = [];
        foreach($efshops as $shp){
            $shopnames[] = $shp->report_name;
            $shopcolor[] = $shp->color_code;
        }


        //EFF PLANT YESTERDAY
        $tddrhrs = 0; $tdlnhrs = 0; $tdotlnhrs = 0;
        foreach($efshops as $shop){
            $tddrhrs += Attendance::where([['shop_id',$shop->id],['date',$yesterday]])->sum(DB::raw('efficiencyhrs'));
            $tdlnhrs += Attendance::where([['shop_loaned_to',$shop->id],['date',$yesterday]])->sum(DB::raw('loaned_hrs'));
            $tdotlnhrs += Attendance::where([['shop_loaned_to',$shop->id],['date',$yesterday]])->sum(DB::raw('otloaned_hrs'));
        }
        $TDinput = $tddrhrs + $tdlnhrs + $tdotlnhrs;
        $TDoutput = Unitmovement::where('datetime_out',$yesterday)->sum('std_hrs');
        $TDplant_eff = ($TDinput > 0) ? round(($TDoutput/$TDinput)*100,0) : 0;

        //EFFPLANT MTD
		$MTDplant_eff = getPlantEfficiency($firstthismonthY, $yesterday);

        //EFF PLANT YTD
        $ytddrhrs = 0; $ytdlnhrs = 0; $ytdotlnhrs = 0;
        foreach($efshops as $shop){
            $ytddrhrs += Attendance::whereBetween('date', [$firstthisyrY, $yesterday])->where('shop_id',$shop->id)->sum(DB::raw('efficiencyhrs'));
            $ytdlnhrs += Attendance::whereBetween('date', [$firstthisyrY, $yesterday])->where('shop_loaned_to',$shop->id)->sum(DB::raw('loaned_hrs'));
            $ytdotlnhrs += Attendance::whereBetween('date', [$firstthisyrY, $yesterday])->where('shop_loaned_to',$shop->id)->sum(DB::raw('otloaned_hrs'));
        }
        $YTDinput = $ytddrhrs + $ytdlnhrs + $ytdotlnhrs;
        $YTDoutput = Unitmovement::whereBetween('datetime_out', [$firstthisyrY, $yesterday])->sum('std_hrs');
        $YTDplant_eff = ($YTDinput > 0) ? round(($YTDoutput/$YTDinput)*100,0) : 0;

        //EFF SHOP YESTERDAY
        $sptddrhrs = 0; $sptdlnhrs = 0; $sptdotlnhrs = 0;
        foreach($efshops as $shop){
            $sptddrhrs = Attendance::where([['shop_id',$shop->id],['date',$yesterday]])->sum(DB::raw('efficiencyhrs'));
            $sptdlnhrs = Attendance::where([['shop_loaned_to',$shop->id],['date',$yesterday]])->sum(DB::raw('loaned_hrs'));
            $sptdotlnhrs = Attendance::where([['shop_loaned_to',$shop->id],['date',$yesterday]])->sum(DB::raw('otloaned_hrs'));
            $TDinput = $sptddrhrs + $sptdlnhrs + $sptdotlnhrs;
            $TDoutput = Unitmovement::where([['datetime_out',$yesterday],['shop_id',$shop->id]])->sum('std_hrs');
            $TDshop_eff[] = ($TDinput > 0) ? round(($TDoutput/$TDinput)*100,0) : 0;
        }

        //EFF SHOP MTD
        foreach($efshops as $shop){
            $spmtddrhrs = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$shop->id)->sum('efficiencyhrs');
            $spmtdlnhrs = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_loaned_to',$shop->id)->sum('loaned_hrs');
            $spmtdotlnhrs = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_loaned_to',$shop->id)->sum('otloaned_hrs');
            $MTDinput = $spmtddrhrs + $spmtdlnhrs + $spmtdotlnhrs;
            $MTDoutput = Unitmovement::whereBetween('datetime_out', [$firstthismonthY, $yesterday])->where('shop_id',$shop->id)->sum('std_hrs');
            $MTDshop_eff[] = ($MTDinput > 0) ? round(($MTDoutput/$MTDinput)*100,0) : 0;
        }

        //ABS PLANT MTD
        $empmarkeds = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->count();
        $expectedHrs = $empmarkeds * 8;
        $hrsworked = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->sum(DB::raw('direct_hrs + indirect_hrs'));
        $absHrs = $expectedHrs - $hrsworked;
        $MTDplantABS = ($expectedHrs > 0) ? round(($absHrs/$expectedHrs)*100,2) : 0.00;

        //ABS SHOP MTD
        foreach($efshops as $shop){
            $empmarkeds = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$shop->id)->count();
            $expectedHrs = $empmarkeds * 8;
            $hrsworked = Attendance::whereBetween('date', [$firstthismonthY, $yesterday])->where('shop_id',$shop->id)->sum(DB::raw('direct_hrs + indirect_hrs'));
            $absHrs = $expectedHrs - $hrsworked;
            $MTDshopABS[] = ($expectedHrs > 0) ? round(($absHrs/$expectedHrs)*100,2) : 0.00;
        }


        //TLA PLANT MTD
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
        $MTDplantTLavail = ($plantTThrs == 0) ? 0 : round(($plantdirect/$plantTThrs)*100,2);

        //TLA SHOP MTD
        foreach($efshops as $shop){
            $direct = 0; $indirect = 0; $tthrs = 0; $shopTThrs = 0; $shopdirect = 0;
            $teamleaders = Employee::where([['team_leader','=','yes'],['shop_id', '=', $shop->id],['status','=','Active']])->get('id');
            foreach($teamleaders as $tl){
                $direct += Attendance::where('staff_id','=',$tl->id)->whereBetween('date', [$firstthismonthY, $yesterday])
                                ->sum(DB::raw('direct_hrs + othours'));
                $indirect += Attendance::where('staff_id','=',$tl->id)->whereBetween('date', [$firstthismonthY, $yesterday])
                            ->sum(DB::raw('indirect_hrs + indirect_othours'));
            }

            $tthrs = ($indirect+$direct == 0) ? 1 : $indirect+$direct;
            $shopTThrs += ($indirect+$direct);
            $shopdirect += $indirect;
            $MTDshopTLavail[] = round(($indirect/$tthrs)*100,2);
        }

        $plantabb = getTarget($yesterday)->absentieesm;
        $planteff = getTarget($yesterday)->efficiency;
        $data = array(
            'plantabb'=>$plantabb,
            'planteff'=>$planteff,
            'planttlav'=>getplantTLAtarget(),
        );

        //return $plantEff;
        return view('graph.plantefficiency')
            ->with('shopnames',json_encode($shopnames,JSON_NUMERIC_CHECK))
            ->with('shopcolor',json_encode($shopcolor,JSON_NUMERIC_CHECK))
            ->with('absentiesm',json_encode($MTDshopABS,JSON_NUMERIC_CHECK))
            ->with('plant_absentpc',$MTDplantABS)

            ->with('shopTLavail',json_encode($MTDshopTLavail,JSON_NUMERIC_CHECK))
            ->with('plantTLAvail',$MTDplantTLavail)

            ->with('shop_eff',json_encode($TDshop_eff,JSON_NUMERIC_CHECK))
            ->with('plantEff',$TDplant_eff)

            ->with('MTDshop_eff',json_encode($MTDshop_eff,JSON_NUMERIC_CHECK))
            ->with('MTDplantEff',$MTDplant_eff)

            ->with('YTDplantEff',$YTDplant_eff)
            ->with($data);
    }





}
