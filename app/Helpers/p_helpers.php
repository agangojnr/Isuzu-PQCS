
<?php
use App\Helpers\uuid;
use Carbon\Carbon as Carbon;
use App\Models\productiontarget\Production_target;
use App\Models\attendance\Attendance;
use App\Models\attendancestatus\Attendance_status;
use App\Models\unitmovement\Unitmovement;
use App\Models\querydefect\Querydefect;
use App\Models\drrtarget\DrrTarget;
use App\Models\drrtargetshop\DrrTargetShop;
use App\Models\shop\Shop;
use App\Models\drr\Drr;
use App\Models\unitsbroughtforward\UnitsBroughtForward;
use App\Models\employee\Employee;
use App\Models\vehicle_units\vehicle_units;
use App\Models\indivtarget\IndivTarget;
use App\Models\gcatarget\GcaTarget;

function broughtfoward($shopid,$date){
    $shopids = [1,2,3,5,6];
    if(in_array($shopid,$shopids)){

        $today = carbon::today()->format('Y-m-d');
        $broughts = UnitsBroughtForward::where('shop_id',$shopid)->first();
        $unitsBF =  $broughts->noofunits;
        $startdate =  $broughts->begindate;

        $sumalltargets = Production_target::whereBetween('shop'.$shopid.'', [$startdate, $date])->sum('noofunits');
        $sumalltargets += $unitsBF;
        $sumactuals = Unitmovement::where('shop_id','=',$shopid)
                    ->whereBetween('datetime_out',[$startdate, $date])->count();
        $balance = $sumactuals - $sumalltargets;
        //$balance =  $unitsBF;
    }else{
        $balance = 0;
    }
    return $balance;
}

function getProductionenddate($shopid,$today){
    $shopoffdays = [1=>4,2=>3,3=>2,5=>1,6=>1,8=>0,10=>0,11=>1,12=>0,13=>0,14=>0,15=>0,16=>0];

    $firstthismonth = Carbon::parse($today)->startOfMonth()->format('Y-m-d');
    $endthismonth = Carbon::parse($today)->endOfMonth()->format('Y-m-d');
    $monthschdates = Production_target::whereBetween('date', [$firstthismonth, $endthismonth])
            ->where('schedule_part','entire')->groupby('date')->orderBy('date', 'ASC')->get(['date']);
    foreach($monthschdates as $schdt){ $mnthprodndays[] = $schdt->date; }

    $allschdates = Production_target::where('schedule_part','entire')->groupby('date')->get(['date']);
    foreach($allschdates as $schdt){ $allprodndays[] = $schdt->date; }

    $pos = array_search(end($mnthprodndays), $allprodndays);
    $startpos = $pos - $shopoffdays[$shopid];
    $lstprodndaythismonth = $allschdates[$startpos];

    return $lstprodndaythismonth['date'];
}

function getProductionstartdate($shopid,$today){
    $shopoffdays = [1=>4,2=>3,3=>2,5=>1,6=>1,8=>0,10=>0,11=>1,12=>0,13=>0,14=>0,15=>0,16=>0];

    $firstthismonth = Carbon::parse($today)->startOfMonth()->format('Y-m-d');
    $endthismonth = Carbon::parse($today)->endOfMonth()->format('Y-m-d');
    $monthschdates = Production_target::whereBetween('date', [$firstthismonth, $endthismonth])
            ->where('schedule_part','entire')->groupby('date')->orderBy('date', 'ASC')->get(['date']);
    foreach($monthschdates as $schdt){ $mnthprodndays[] = $schdt->date; }

    $allschdates = Production_target::where('schedule_part','entire')->groupby('date')->get(['date']);
    foreach($allschdates as $schdt){ $allprodndays[] = $schdt->date; }

    $pos = array_search($mnthprodndays[0], $allprodndays);
    $startpos = $pos - $shopoffdays[$shopid];
    $fstprodndaythismonth = $allschdates[$startpos];

    return $fstprodndaythismonth['date'];
}

function getTarget($date){
    $starts = [1=>'01-01',2=>'04-01',3=>'07-01',4=>'10-01'];
    $ends = [1=>'03-31',2=>'06-30',3=>'09-30',4=>'12-31'];

    $year = Carbon::createFromFormat('Y-m-d', $date)->format('Y');

    $fst1 = Carbon::createFromFormat('Y-m-d', $year.'-'.$starts[1])->format('Y-m-d');
    $fst2 = Carbon::createFromFormat('Y-m-d', $year.'-'.$starts[2])->format('Y-m-d');
    $fst3 = Carbon::createFromFormat('Y-m-d', $year.'-'.$starts[3])->format('Y-m-d');
    $fst4 = Carbon::createFromFormat('Y-m-d', $year.'-'.$starts[4])->format('Y-m-d');
    $last1 = Carbon::createFromFormat('Y-m-d', $year.'-'.$ends[1])->format('Y-m-d');
    $last2 = Carbon::createFromFormat('Y-m-d', $year.'-'.$ends[2])->format('Y-m-d');
     $last3 = Carbon::createFromFormat('Y-m-d', $year.'-'.$ends[3])->format('Y-m-d');
    $last4 = Carbon::createFromFormat('Y-m-d', $year.'-'.$ends[4])->format('Y-m-d');

    if(carbon::parse($fst1) <= $date && carbon::parse($last1) >= $date){
        $quart = '1st Quarter';
    }elseif(carbon::parse($fst2) <= $date && carbon::parse($last2) >= $date){
        $quart = '2nd Quarter';
    }elseif(carbon::parse($fst3) <= $date && carbon::parse($last3) >= $date){
        $quart = '3rd Quarter';
    }elseif(carbon::parse($fst4) <= $date && carbon::parse($last4) >= $date){
        $quart = '4th Quarter';
    }

    $targets = IndivTarget::where([['year',$year],['yearquarter',$quart]])->first();
    $targets = ($targets == "") ? 0 : $targets;

    return $targets;
}

function getplantTLAtarget(){
    $shops = Shop::where('check_shop','=','1')->get(['id','report_name']);
    $normemps = 0; $noteaml = 0;
    foreach($shops as $sp){
        $normemps += Employee::where([['team_leader','no'],['shop_id',$sp->id],['status','Active']])->count();
        $noteaml += Employee::where([['team_leader','yes'],['shop_id',$sp->id],['status','Active']])->count();
    }
    $tlavailability = ($normemps/($normemps+$noteaml)) * 100;
    return $tlavailability;
}

function getPlantEfficiency($start, $end){
    $efshops = Shop::where('check_shop','=','1')->get(['report_name','id']);
    $mtddrhrs = 0; $mtdlnhrs = 0; $mtdotlnhrs = 0;
    foreach($efshops as $shop){
        $mtddrhrs += Attendance::whereBetween('date', [$start, $end])->where('shop_id',$shop->id)->sum(DB::raw('efficiencyhrs'));
        $mtdlnhrs += Attendance::whereBetween('date', [$start, $end])->where('shop_loaned_to',$shop->id)->sum(DB::raw('loaned_hrs'));
        $mtdotlnhrs += Attendance::whereBetween('date', [$start, $end])->where('shop_loaned_to',$shop->id)->sum(DB::raw('otloaned_hrs'));
    }
    $MTDinput = $mtddrhrs + $mtdlnhrs + $mtdotlnhrs;
    $MTDoutput = Unitmovement::whereBetween('datetime_out', [$start, $end])->sum('std_hrs');

    $MTDplant_eff = ($MTDinput > 0) ? round(($MTDoutput/$MTDinput)*100,2) : 0;

    return $MTDplant_eff;
}

function getGCATarget($date){
    $starts = [1=>'01-01',2=>'04-01',3=>'07-01',4=>'10-01'];
    $ends = [1=>'03-31',2=>'06-30',3=>'09-30',4=>'12-31'];

    $year = Carbon::createFromFormat('Y-m-d', $date)->format('Y');

    $fst1 = Carbon::createFromFormat('Y-m-d', $year.'-'.$starts[1])->format('Y-m-d');
    $fst2 = Carbon::createFromFormat('Y-m-d', $year.'-'.$starts[2])->format('Y-m-d');
    $fst3 = Carbon::createFromFormat('Y-m-d', $year.'-'.$starts[3])->format('Y-m-d');
    $fst4 = Carbon::createFromFormat('Y-m-d', $year.'-'.$starts[4])->format('Y-m-d');
    $last1 = Carbon::createFromFormat('Y-m-d', $year.'-'.$ends[1])->format('Y-m-d');
    $last2 = Carbon::createFromFormat('Y-m-d', $year.'-'.$ends[2])->format('Y-m-d');
     $last3 = Carbon::createFromFormat('Y-m-d', $year.'-'.$ends[3])->format('Y-m-d');
    $last4 = Carbon::createFromFormat('Y-m-d', $year.'-'.$ends[4])->format('Y-m-d');

    if(carbon::parse($fst1) <= $date && carbon::parse($last1) >= $date){
        $quart = '1st Quarter';
    }elseif(carbon::parse($fst2) <= $date && carbon::parse($last2) >= $date){
        $quart = '2nd Quarter';
    }elseif(carbon::parse($fst3) <= $date && carbon::parse($last3) >= $date){
        $quart = '3rd Quarter';
    }elseif(carbon::parse($fst4) <= $date && carbon::parse($last4) >= $date){
        $quart = '4th Quarter';
    }

    $targets = GcaTarget::where([['year',$year],['yearquarter',$quart]])->first();
    $targets = ($targets == "") ? 0 : $targets;

    return $targets;
}

function getshopEfficiency($start,$end,$shopid){
    $spmtddrhrs = Attendance::whereBetween('date', [$start, $end])->where('shop_id',$shopid)->sum(DB::raw('efficiencyhrs'));
    $spmtdlnhrs = Attendance::whereBetween('date', [$start, $end])->where('shop_loaned_to',$shopid)->sum(DB::raw('loaned_hrs'));
    $spmtdotlnhrs = Attendance::whereBetween('date', [$start, $end])->where('shop_loaned_to',$shopid)->sum(DB::raw('otloaned_hrs'));

$spMTDinput = $spmtddrhrs + $spmtdlnhrs + $spmtdotlnhrs;
$spMTDoutput = Unitmovement::whereBetween('datetime_out', [$start, $end])->where('shop_id',$shopid)->sum('std_hrs');

$spMTDplant_eff = ($spMTDinput > 0) ? round(($spMTDoutput/$spMTDinput)*100) : 0;

return $spMTDplant_eff;
}

function getshopTLAtarget($shopid){
    $normemps = Employee::where([['team_leader','no'],['shop_id',$shopid],['status','Active']])->count();
    $noteaml = Employee::where([['team_leader','yes'],['shop_id',$shopid],['status','Active']])->count();
    $tlavailability = ($normemps/($normemps+$noteaml))*100;
    return $tlavailability;
}
