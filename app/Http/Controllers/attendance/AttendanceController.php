<?php

namespace App\Http\Controllers\attendance;

use App\Models\attendance\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\employee\Employee;
use App\Models\unitmovement\Unitmovement;
use App\Models\unit_model\Unit_model;
use App\Models\vehicle_units\vehicle_units;
use App\Models\std_working_hr\Std_working_hr;
//use App\Models\attendancepreview\AttendancePreview;
use App\Models\defaultattendance\DefaultAttendanceHRS;
use App\Models\attendancestatus\Attendance_status;
use App\Models\reviewconversation\Review_conversation;
use App\Models\productiontarget\Production_target;
use App\Models\workschedule\WorkSchedule;
use App\Models\shop\Shop;
use App\Models\targets\Target;
use App\Models\indivtarget\IndivTarget;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use App\Exports\AttndRegisterView;
use Excel;
use PDF;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        //Permission::create(['name' => 'attendance-mark']);
        //Permission::create(['name' => 'Effny-dashboard']);
        //Permission::create(['name' => 'people-report']);
        //Permission::create(['name' => 'people-summary']);
        //Permission::create(['name' => 'set-default']);
        //Permission::create(['name' => 'manage-target']);
        //Permission::create(['name' => 'view-target']);
        //Permission::create(['name' => 'set-stdhrs']);
    function __construct()
    {
         $this->middleware('permission:attendance-mark', ['only' => ['markattencance','attendance_view','store']]);
         $this->middleware('permission:people-report', ['only' => ['headcount']]);
         $this->middleware('permission:people-summary', ['only' => ['prodnoutput']]);
         $this->middleware('permission:manage-target', ['only' => ['createtargets','savetargets']]);
         $this->middleware('permission:view-target', ['only' => ['settargets']]);

         $this->middleware('permission:direct-manpower', ['only' => ['headcount']]);
         $this->middleware('permission:stdhrs-generated', ['only' => ['prodnoutput']]);
         $this->middleware('permission:stdActual-hours', ['only' => ['weeklystdhrs']]);
         $this->middleware('permission:plant-register', ['only' => ['plantattendancereg']]);
         $this->middleware('permission:target-report', ['only' => ['peopleAttreport']]);

    }

    //Marking attendance
    public function markattencance(Request $request){

            $mdate =$request->input('mdate');
            $shopid = $request->input('shop');

            $empexist = Employee::where([['shop_id','=',$shopid],['outsource','=','no']])->first();
            if(empty($empexist)){
                Toastr::error('Sorry, There is no employee in that shop/section.','Whooops!');
                return back();
            }

        $date = Carbon::createFromFormat('m/d/Y', $mdate)->format('Y-m-d');
        $prodday = Production_target::where('date','=',$date)->first();
        $holi = WorkSchedule::where('date','=',$date)->value('holidayname');
        if(!empty($holi)){
            $dayname = $holi;
        }else{
            $dayOfTheWeek = Carbon::parse($mdate)->dayOfWeek;
            $weekMap = [0 => 'Sun',1 => 'Mon',2 => 'Tue',3 => 'Wed',4 => 'Thu',5 => 'Fri',6 => 'Sat'];
            $dayname = $weekMap[$dayOfTheWeek];
        }


        $checkconfirmed = Attendance_status::where([['shop_id','=',$shopid],['date','=',$date]])->first();
        /*if($checkconfirmed != null){
            Toastr::error('Attendance already confirmed.','Access denied!');
            return back();
        }*/

        $shopname = Shop::where([['id', $shopid],['overtime','=','1']])->value('report_name');
        $allshops = Shop::where('overtime','=','1')->get(['id','report_name']);
        $shopno = 0;
        foreach($allshops as $one){
            if($one->report_name == $shopname){
                break;
            }
            $shopno++;
        }

        unset($allshops[$shopno]);

            $st = Attendance_status::where([['date', '=', $date], ['shop_id', '=', $shopid]])->value('status_name');
            $marked = ($st == "") ? "Not marked" : $st;
            $indirectshop = Shop::where('id','=',$shopid)->value('check_shop');

            //SUBMISSION STATUS
            $attstatus = Attendance_status::where([['shop_id','=',$shopid],['date','=',$date]])->first();
            //CONVERSATION
            $statusid = Attendance_status::where([['shop_id','=',$shopid],['date','=',$date]])->value('id');
            //if(!empty($statusid)){
               $conversation = Review_conversation::where('statusid','=',$statusid)
                                ->get(['user_id','statusid','sender','message','created_at']);
            //}

            if($st != ""){

                $staffs = Attendance::where([['date', '=', $date], ['shop_id', '=', $shopid]])->get();

                    $id = DefaultAttendanceHRS::orderBy('id', 'desc')->take(1)->value('id');

                    $hrslimit = DefaultAttendanceHRS::where('id','=',$id)->value('hrslimit');
                    $checkshop = Shop::where('id','=',$shopid)->value('check_shop');
                    $overtime = DefaultAttendanceHRS::where('id','=',$id)->value('overtime');

                    $data = array(
                        'num' => 1, 'direct'=> 0, 'indirect'=> 0, 'hrslimit'=>$hrslimit,
                         'i'=>0,
                        'staffs'=>$staffs, 'overtime'=>$overtime, 'outsourcestaffs'=>[],
                        'directname' => ($checkshop == 1) ? 'Direct' : 'Indirect',
                        'shop' => $shopname,'conversation'=>$conversation,
                        'shopid' => $shopid,
                        'shops' => $allshops,
                        'date' => $date, 'dayname'=>$dayname,
                        'marked' => $marked, 'prodday'=>$prodday,
                        'attstatus'=>$attstatus,'indirectshop'=>$indirectshop,
                    );
                    return view('attendances.index')->with($data);

            }else{
                $id = DefaultAttendanceHRS::orderBy('id', 'desc')->take(1)->value('id');
                $direct = DefaultAttendanceHRS::where('id','=',$id)->value('direct');
                $indirect = DefaultAttendanceHRS::where('id','=',$id)->value('indirect');
                $hrslimit = DefaultAttendanceHRS::where('id','=',$id)->value('hrslimit');
                $overtime = DefaultAttendanceHRS::where('id','=',$id)->value('overtime');
                $checkshop = Shop::where('id','=',$shopid)->value('check_shop');
                $directname = ($checkshop == 1) ? 'Direct' : 'Indirect';

                $data = array(
                    'num' => 1, 'direct'=> $direct, 'indirect'=> $indirect, 'hrslimit'=>$hrslimit,
                    'staffs' => Employee::where([['shop_id', $shopid],['status','=','Active'],['outsource','=','no']])
                                ->get(['id','staff_no','staff_name','team_leader','outsource','outsource_date']),
                    'outsourcestaffs' => Employee::where([['shop_id', $shopid],['status','=','Active'],['outsource_date','=',$date],['outsource','=','yes']])
                                ->get(['id','staff_no','staff_name','team_leader','outsource','outsource_date']),
                    'shops' => $allshops, 'overtime'=>$overtime, 'directname'=>$directname,
                    'set' => DefaultAttendanceHRS::All(),
                    'shop' => $shopname, 'indirectshop'=>$indirectshop,
                    'shopid' => $shopid, 'dayname'=>$dayname,
                    'date' => $date, 'prodday'=>$prodday,'conversation'=>$conversation,
                    'marked' => $marked, 'attstatus'=>$attstatus,
                );
                return view('attendances.index')->with($data);

            }

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendance_view()
    {
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        $shops = Shop::where('overtime','=','1')->get(['report_name','id']);
        $selectshops = Shop::where('overtime','=','1')->pluck('report_name','id');
        foreach($shops as $sp){
            $names[] = $sp->report_name;

            $check1 = Attendance_status::where([['date', '=', $today], ['shop_id', '=', $sp->id]])->value('status_name');
            $checky1 = Attendance_status::where([['date', '=', $yesterday], ['shop_id', '=', $sp->id]])->value('status_name');
            $confirmedtoday[] = ($check1 == "approved") ? "check" : "";
            $confirmedyesterday[] = ($checky1 == "approved") ? "check" : "";

            $check = Attendance_status::where([['date', '=', $today], ['shop_id', '=', $sp->id]])->value('status_name');
            $colord[] = ($check == "" || $check == 'saved' || $check == "reveiw") ? "danger" : "success";
            $checky = Attendance_status::where([['date', '=', $yesterday], ['shop_id', '=', $sp->id]])->value('status_name');
            $colory[] = ($checky == "" || $checky == 'saved' || $checky == "reveiw") ? "danger" : "success";

            $count_TT[] = Employee::where([['shop_id', '=', $sp->id],['status','=','Active'],['outsource','=','no']])->count('id');

            $empids = Employee::where([['shop_id','=',$sp->id],['status','=','Active'],['outsource','=','no']])->get('id');
            $presenttoday = 0; $presentyesterday = 0;
            foreach($empids as $empid){
                $hrs = Attendance::Where([['date', '=', $today],['staff_id','=',$empid->id]])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
                ($hrs > 0) ? $presenttoday = $presenttoday + 1 : $presenttoday = $presenttoday;

                $hrs1 = Attendance::Where([['date', '=', $yesterday],['staff_id','=',$empid->id]])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
                ($hrs1 > 0) ? $presentyesterday = $presentyesterday + 1 : $presentyesterday = $presentyesterday;
            }
            $count_presenttoday[] = $presenttoday;
            $count_presentyesterday[] = $presentyesterday;

        }

//INSERTING STATUS
/*$attends =Attendance::groupBy('date')->groupBy('shop_id')->get(['date','shop_id']);
foreach($attends as $attend){
    $check = Attendance_status::where([['date','=',$attend->date],['shop_id','=',$attend->shop_id]])->first();
    if(empty($check)){
        $stat = new Attendance_status;
        $stat->shop_id = $attend->shop_id;
        $stat->date = $attend->date;
        $stat->status_name = 'approved';
        $stat->workdescription = 'Work description...';
        $stat->user_id = auth()->user()->id;

        $stat->save();
    }
}*/

      $proddayys = Production_target::groupBy('date')->whereBetween('date',['2021-11-16',Carbon::today()->format('Y-m-d')])->get('date');
        $unlogged = []; $saveds = []; $reviews = [];
        foreach($proddayys as $dayy){
            $logged = Attendance_status::where([['date','=',$dayy->date],['shop_id','=',Auth()->User()->section]])->first();
            if($logged == ""){
                $unlogged[] = Carbon::createFromFormat('Y-m-d', $dayy->date)->format('d M Y');
            }

            $saved = Attendance_status::where([['date','=',$dayy->date],['shop_id','=',Auth()->User()->section]])->value('status_name');
            if($saved == "saved"){
                $saveds[] = Carbon::createFromFormat('Y-m-d', $dayy->date)->format('d M Y');
            }

            $review = Attendance_status::where([['date','=',$dayy->date],['shop_id','=',Auth()->User()->section]])->value('status_name');
            if($review == "review"){
                $reviews[] = Carbon::createFromFormat('Y-m-d', $dayy->date)->format('d M Y');
            }
        }

        //return $count_presenttoday;
        $data = array(
            'unlogged'=>$unlogged,'saveds'=>$saveds,'reviews'=>$reviews,
            'shops' => $selectshops,
            'names' =>$names,
            'colord'=>$colord,
            'colory'=>$colory,
            'count_TT'=>$count_TT,
            'count_presenttoday'=>$count_presenttoday,
            'count_presentyesterday'=>$count_presentyesterday,
            'confirmedtoday'=>$confirmedtoday,
            'confirmedyesterday'=>$confirmedyesterday,

        );
        return view('attendances.attendance_view')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->input('button') == "reveiwsubmitted"){
            $validator = Validator::make($request->all(), [
                'message' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error('Sorry! Provide some response text.');
                return back();
            }
        }
        $validator = Validator::make($request->all(), [
            'direct' => 'required',
            'indirect' => 'required',
            'overtime' => 'required',
            'authhrs' => 'required',
            'indovertime'=>'required',
            'workdescription'=>'required',
            'workdescriptionall'=>'required',
        ]);

        if ($validator->fails()) {
            Toastr::error('Sorry! All fields are required.');
            return back();
        }

        $date = $request->input('date');
        $shop_id = $request->shop_id;

        $staffid = $request->staff_id;
        $direct = $request->direct;
        $indirect = $request->indirect;

        $dirshopto = $request->dirshopto;
        $loandir = $request->loandir;

        $overtime = $request->overtime;
        $authhrs = $request->authhrs;
        $overshopto = $request->overshopto;
        $loanov = $request->loanov;
        $indovertime = $request->indovertime;
        $workdescription = $request->workdescription;

        $marked = Attendance::where([['date', '=', $date], ['shop_id', '=', $shop_id]])->first();
        if($marked == null){

            try{
                //return count($loandir);
                DB::beginTransaction();
                for($i = 0; $i < count($staffid); $i++)
                {
                    $attend = new Attendance;
                        $attend->date = $date;
                        $attend->shop_id = $shop_id;
                        $attend->staff_id = $staffid[$i];

                            $direct_hrs = ($direct[$i] == null)? 0 : $direct[$i];
                        $attend->direct_hrs = $direct_hrs ;
                            $indirect_hrs = ($indirect[$i] == null)? 0: $indirect[$i];
                        $attend->indirect_hrs = $indirect_hrs;
                            $loaned_hrs = ($loandir[$i] == null)? 0 : $loandir[$i];
                        $attend->loaned_hrs = ($loandir[$i] == null)? 0 : $loandir[$i];
                        $attend->shop_loaned_to = ($dirshopto[$i] == null)? 0 : $dirshopto[$i];

                        $attend->auth_othrs = $authhrs[$i];
                            $indothours = $indovertime[$i];
                        $attend->indirect_othours = $indothours;
                            $othours = $overtime[$i];
                        $attend->othours = $othours;
                            $otloaned_hrs = ($loanov[$i] == null)? 0 : $loanov[$i];
                        $attend->otloaned_hrs = $otloaned_hrs;
                        $attend->workdescription = $workdescription[$i];

                        $attend->otshop_loaned_to = ($overshopto[$i] == null)? 0 : $overshopto[$i];

                        $attend->efficiencyhrs = (($direct_hrs+$indirect_hrs) * 0.97875) + $othours + $indothours;
                        $attend->user_id = auth()->user()->id;

                       $attend->save();
                }

                //ATTENDANCE STATUS
                    $status = new Attendance_status;
                    $status->date = $date;
                    $status->shop_id = $shop_id;
                    $status->status_name = $request->input('button');
                    $status->workdescription = $request->input('workdescriptionall');
                    $status->user_id = auth()->user()->id;
                    $status->save();

                DB::commit();
                Toastr::success('Attendance saved successfully','Saved');
                return redirect('/attendance_view');
            }
            catch(\Exception $e){
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                Toastr::error('Sorry! An error occured attendance not saved.','Error');
                return $e->getMessage();
            }



        }else{
            //Awaiting some code
            $markedid = Attendance::where([['date', '=', $date], ['shop_id', '=', $shop_id]])->get('id');//->first();
            try{
                DB::beginTransaction();
            for($i = 0; $i < count($staffid); $i++)
            {
                $attend = Attendance::find($markedid[$i]->id);

                $attend->date = $date;
                $attend->shop_id = $shop_id;
                $attend->staff_id = $staffid[$i];

                    $direct_hrs = ($direct[$i] == null)? 0 : $direct[$i];
                $attend->direct_hrs = $direct_hrs ;
                    $indirect_hrs = ($indirect[$i] == null)? 0: $indirect[$i];
                $attend->indirect_hrs = $indirect_hrs;
                    $loaned_hrs = ($loandir[$i] == null)? 0 : $loandir[$i];
                $attend->loaned_hrs = ($loandir[$i] == null)? 0 : $loandir[$i];
                $attend->shop_loaned_to = ($dirshopto[$i] == null)? 0 : $dirshopto[$i];

                $attend->auth_othrs = $authhrs[$i];
                    $indothours = $indovertime[$i];
                $attend->indirect_othours = $indothours;
                    $othours = $overtime[$i];
                $attend->othours = $othours;
                    $otloaned_hrs = ($loanov[$i] == null)? 0 : $loanov[$i];
                $attend->otloaned_hrs = $otloaned_hrs;
                $attend->workdescription = $workdescription[$i];

                $attend->otshop_loaned_to = ($overshopto[$i] == null)? 0 : $overshopto[$i];

                $attend->efficiencyhrs = (($direct_hrs+$indirect_hrs) * 0.97875) + $othours + $indothours;
                $attend->user_id = auth()->user()->id;

                $attend->save();
            }


             //ATTENDANCE STATUS
             $statusid = Attendance_status::where([['shop_id','=',$shop_id],['date','=',$date]])->value('id');

                 $status = Attendance_status::find($statusid);
                 if($request->input("button") == "reveiwsubmitted"){
                    $status->status_name = "submitted";
                 }else{
                    $status->status_name = $request->input('button');
                }
                 $status->workdescription = $request->input('workdescriptionall');
                 $status->save();

                if($request->input("button") == "reveiwsubmitted"){
                    $review = new Review_conversation;
                    $review->user_id = auth()->user()->id;
                    $review->statusid = $request->input('statusid');
                    $review->sender = $request->input('sender');
                    $review->message = $request->input('message');
                    $review->save();
                }

            DB::commit();
                Toastr::success('Attendance updated successfully','Saved');
                return back();
            }
            catch(\Exception $e){
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                Toastr::error('Sorry! An error occured attendance not Updated.','Error');
                return $e->getMessage();
            }

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function attendancereport(Request $request)
    {
        if($request->input()){
            $date = $request->input('mdate');
           $date1 = Carbon::createFromFormat('F Y', $date)->format('Y-m-d');
           $date = Carbon::createFromFormat('Y-m-d', $date1);
           $firstthismonth = $date->startOfMonth()->toDateString();
           $endthismonth = $date->endOfMonth()->toDateString();
           $today = $endthismonth;
           $shopid = $request->input('shop');
       }else{
           $today = Carbon::today()->format('Y-m-d');
           $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
           $shopid = 1;
       }

       //PRODUCTION DAYS
       $allschdates = Production_target::whereBetween('date', [$firstthismonth, $today])
                    ->groupby('date')->get(['date']);
            if(count($allschdates) == 0){
                Toastr::error('Sorry, There is no schedules for the month.','Whoops!');
                return back();
                }
            foreach($allschdates as $schdt){  $prodndays[] = $schdt->date; }

       $shopname = Shop::where('id','=',$shopid)->value('report_name');

       $employees = Employee::where('shop_id','=',$shopid)->get(['id','staff_no','staff_name']);
       foreach($employees as $emp){

            $tthrs = 0;
            for($n = 0; $n < count($prodndays); $n++){
                $dates[] = Carbon::createFromFormat('Y-m-d', $prodndays[$n])->format('jS');

                $hours = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
                $emphrs[$emp->id][] = $hours; $tthrs += $hours;
            }
            $ttemphrs[$emp->id] = $tthrs;
       }

       //Per date
       $tthrs = 0; $ttsum = 0;
            for($n = 0; $n < count($prodndays); $n++){
                $tthh = Attendance::where([['date','=',$prodndays[$n]],['shop_id','=',$shopid]])
                    ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
                $ttsum += $tthh;  $hrsperdate[] = $tthh;

            }

       //return $emphrs;
        $data = array(
            'employees' => $employees,
            'today'=>$today,
            'dates'=>$dates,
            'count'=>count($prodndays),
            'emphrs'=>$emphrs, 'ttemphrs'=>$ttemphrs, 'hrsperdate'=>$hrsperdate, 'ttsum'=>$ttsum,
            'shopname'=>$shopname,
            'selectshops'=>Shop::where('overtime','=',1)->get(['report_name','id']),

        );

        return view('attendances.attendancerpt')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function searchsummaryreport(Request $request)
    {
        $shopid = $request->shop;
        $shopname = Shop::where('id', $shopid)->value('shop_name');
       $shops = Shop::pluck('shop_name', 'id');

        $shops1 = Shop::All();
        foreach($shops1 as $sp){
            $names[] = $sp->shop_name;
            $mkd = Attendance::where([['date', '=', Carbon::today()->format('Y-m-d')], ['shop_id', '=', $sp->id]])->first('id');
            if($mkd != null){ $colord[] = "success";}
                    else{ $colord[] = "danger";}
        }

        $today = Carbon::now();
        $marked = [];
        for ($i=1; $i<=30; $i++) {
            $today->subDays(1);
            if($today->format('l') != "Sunday"){
                $dates[] = $today->format('jS F Y');
                $days[] = $today->format('l');
                $date = $today->format('Y-m-d');
                $mk = Attendance::where([['date', '=', $date], ['shop_id', '=', $shopid]])->first();
                $directSum[] = Attendance::where([['date', '=', $date], ['shop_id', '=', $shopid]])->sum('direct_hrs');
                $indirectSum[] = Attendance::where([['date', '=', $date], ['shop_id', '=', $shopid]])->sum('indirect_hrs');
                $loanedSum[] = Attendance::where([['date', '=', $date], ['shop_id', '=', $shopid]])->sum('loaned_hrs');
                //$shop_id[] = Attendance::where('date', '=', $date)->sum('loaned_hrs');
                if($mk != null){$marked[] = "Marked"; $color[] = "success";}
                else{$marked[] = "Not Marked"; $color[] = "danger";}
            }
        }

        $data = array(
            'dates' => $dates,
            'days' => $days,
            'marked' => $marked, 'color' => $color,
            'directSum' => $directSum,
            'indirectSum'=>$indirectSum,
            'loanedSum'=> $loanedSum,
            'shops' => $shops,
            'shop' => $shopname,
            'names' => $names, 'colord' =>$colord,
        );

        return view('attendances.attendancesummary')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function attendacesummary(){
        $shops = Shop::All();
        $selectshops = Shop::pluck('shop_name','id');
        foreach($shops as $sp){
            $names[] = $sp->shop_name;
            $mkd = Attendance::where([['date', '=', Carbon::today()->format('Y-m-d')], ['shop_id', '=', $sp->id]])->first('id');
            if($mkd != null){ $colord[] = "success";}
                    else{ $colord[] = "danger";}
        }

        //return $colord;


        $today = Carbon::now();
        $marked = [];
        for ($i=1; $i<=30; $i++) {
            $today->subDays(1);

            foreach($shops as $shop){
                if($today->format('l') != "Sunday"){
                    $dates[] = $today->format('jS F Y');
                    $days[] = $today->format('l');
                    $date = $today->format('Y-m-d');
                    $mk = Attendance::where([['date', '=', $date], ['shop_id', '=', $shop->id]])->first();
                    $directSum[] = Attendance::where([['date', '=', $date], ['shop_id', '=', $shop->id]])->sum('direct_hrs');
                    $indirectSum[] = Attendance::where([['date', '=', $date], ['shop_id', '=', $shop->id]])->sum('indirect_hrs');
                    $loanedSum[] = Attendance::where([['date', '=', $date], ['shop_id', '=', $shop->id]])->sum('loaned_hrs');
                    $shopname[] = $shop->shop_name;
                    if($mk != null){$marked[] = "Marked"; $color[] = "success";}
                    else{$marked[] = "Not Marked"; $color[] = "danger";}
                }
            }
        }

        $data = array(
            'dates' => $dates,
            'days' => $days,
            'marked' => $marked, 'color' => $color,
            'directSum' => $directSum,
            'indirectSum'=>$indirectSum,
            'loanedSum'=> $loanedSum,
            'shops' => $selectshops,
            'shopnames' => $shopname,
            'names' =>$names,
            'colord'=>$colord,
        );

        return view('attendances.attendancesummary')->with($data);
    }
public function headcount(Request $request){
        if($request->input()){ //return  $request->input('mdate');
            $daterange = $request->input('daterange');
            $datearr = explode('-',$daterange);
            $datefrom = Carbon::parse($datearr[0])->format('Y-m-d');
            $dateto = Carbon::parse($datearr[1])->format('Y-m-d');
        }else{
            $dateto = Carbon::now()->format('Y-m-d');
            $datefrom = Carbon::create($dateto)->startOfMonth()->format('Y-m-d');
        }


        $shops = Shop::where('overtime','=','1')->get(['report_name','id','check_shop']);
        $TTprdnhrs = 0; $AllTThrs = 0; $tthc = 0; $ttdirect = 0; $ttindirect = 0; $tttl = 0; $ttpresent = 0;
        $mtddrhrs = 0; $mtdlnhrs = 0; $mtdotlnhrs = 0;
        foreach($shops as $sp){
            $headcount = Employee::where([['shop_id', '=', $sp->id],['status','=','Active']])
                            ->whereBetween('created_at', ['2021-08-01', $dateto])->count('id');
                $ttheadcount[$sp->id] = $headcount;
                $tthc += $headcount;

            $teaml = Employee::where([['shop_id', '=', $sp->id],['status','=','Active'],['team_leader','=','yes']])
                            ->whereBetween('created_at', ['2021-08-01', $dateto])->count('id');
                $countTL[$sp->id] = $teaml;
                $tttl += $teaml;

            //MTD
            $Mtdhrs = Attendance::whereBetween('date', [$datefrom, $dateto])
                            ->Where('shop_id','=',$sp->id)
                            ->sum(DB::raw('direct_hrs + indirect_hrs + othours'));
                $spmtdlnhrs = Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to',$sp->id)->sum('loaned_hrs');
                $spmtdotlnhrs = Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to',$sp->id)->sum('otloaned_hrs');
                $ttshopshrs = $Mtdhrs + $spmtdlnhrs + $spmtdotlnhrs;
                $MTDtthrs[$sp->id] = $ttshopshrs;
                $AllTThrs += $ttshopshrs ;

            //Production shops
            if($sp->check_shop == 1){
                $spmtddrhrs = Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_id',$sp->id)->sum('efficiencyhrs');
                $spmtdlnhrs = Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to',$sp->id)->sum('loaned_hrs');
                $spmtdotlnhrs = Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to',$sp->id)->sum('otloaned_hrs');
                $shopPrdnhrs = $spmtddrhrs + $spmtdlnhrs + $spmtdotlnhrs;
                $MTDPrdnhrs[$sp->id] = $shopPrdnhrs;
                $TTprdnhrs += $shopPrdnhrs;

                //EFFPLANT MTD
                $mtddrhrs += Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_id',$sp->id)->sum(DB::raw('efficiencyhrs'));
                $mtdlnhrs += Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to',$sp->id)->sum(DB::raw('loaned_hrs'));
                $mtdotlnhrs += Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to',$sp->id)->sum(DB::raw('otloaned_hrs'));

                $spMTDinput = $shopPrdnhrs;//$mtddrhrs + $mtdlnhrs + $mtdotlnhrs;
                $spMTDoutput = Unitmovement::whereBetween('datetime_out', [$datefrom, $dateto])->where('shop_id',$sp->id)->sum('std_hrs');
                $spMTDplant_eff[$sp->id] = ($spMTDinput > 0) ? round(($spMTDoutput/$spMTDinput)*100,2) : 0;
            }else{
                $MTDPrdnhrs[$sp->id] = "--";
                $spMTDplant_eff[$sp->id] = "--";
            }

        }

            $MTDinput = $mtddrhrs + $mtdlnhrs + $mtdotlnhrs;
            $MTDoutput = Unitmovement::whereBetween('datetime_out', [$datefrom, $dateto])->sum('std_hrs');
            $lcvfinal = Unitmovement::whereBetween('datetime_out', [$datefrom, $dateto])->where('shop_id',13)->sum('std_hrs');
            $MTDoutput -= $lcvfinal;
            $MTDplant_eff = ($MTDinput > 0) ? round(($MTDoutput/$MTDinput)*100,2) : 0;

        $range = Carbon::createFromFormat('Y-m-d', $datefrom)->format('jS M Y').' To '.Carbon::createFromFormat('Y-m-d', $dateto)->format('jS M Y');

        //return $spMTDplant_eff;
        $data = array(
            'shops'=>$shops,
            'ttheadcount'=>$ttheadcount,
            'countTL'=>$countTL,
            'MTDtthrs'=>$MTDtthrs,
            'MTDPrdnhrs'=>$MTDPrdnhrs,
            'TTprdnhrs'=>$TTprdnhrs,    'tttl'=>$tttl,
            'AllTThrs'=>$AllTThrs,      'tthc'=>$tthc,
            'MTDplant_eff'=>$MTDplant_eff,  'spMTDplant_eff'=>$spMTDplant_eff,


            'range'=>$range
        );
        return view('attendances.headcount')->with($data);
    }

    //PRODUCTION OUTPUT
    public function prodnoutput(Request $request){
        if($request->input()){ //return  $request->input('mdate');
            $daterange = $request->input('daterange');
            $datearr = explode('-',$daterange);
            $datefrom = Carbon::parse($datearr[0])->format('Y-m-d');
            $dateto = Carbon::parse($datearr[1])->format('Y-m-d');
        }else{
            $dateto = Carbon::now()->format('Y-m-d');
            $datefrom = Carbon::create($dateto)->startOfMonth()->format('Y-m-d');
        }

        $shops = Shop::where('check_shop','=','1')->get(['id','report_name']);

        $models = Unit_model::get(['id','model_name']);


        //$DAYmodelcount = 0; $WTDmodelcount = 0; $MTDmodelcount = 0; $modelstdhrs = 0;
        $ttstdhours = 0;
        foreach($shops as $sp){
            //Models MTD
            $model[$sp->id] = DB::table('unit_movements')
                ->join('vehicle_units', 'vehicle_units.id', '=', 'unit_movements.vehicle_id')
                ->join('unit_models', 'unit_models.id', '=', 'vehicle_units.model_id')
                ->whereBetween('datetime_out', [$datefrom, $dateto])
                ->where('shop_id','=',$sp->id)
                ->groupBy('unit_models.id')
                ->get(['unit_models.id']);
            $rowspan[$sp->id] = (count($model[$sp->id]) == 0) ? 1 : count($model[$sp->id]);

            //return count($model[$sp->id]);

            if(count($model[$sp->id]) > 0){
                $tthrspmd = 0;
            foreach($model[$sp->id] as $md){
                //No of units per model MTD
                $mtd = DB::table('unit_movements')
                    ->join('vehicle_units', 'vehicle_units.id', '=', 'unit_movements.vehicle_id')
                    ->join('unit_models', 'unit_models.id', '=', 'vehicle_units.model_id')
                    ->whereBetween('datetime_out', [$datefrom, $dateto])->where('shop_id','=',$sp->id)
                    ->where('unit_models.id','=',$md->id)->count();
                $MTDmodelcount[$sp->id][$md->id] = ($mtd) ? $mtd : 0;


                //STD hours per model
                $hrspmd = DB::table('unit_movements')
                    ->join('vehicle_units', 'vehicle_units.id', '=', 'unit_movements.vehicle_id')
                    ->join('unit_models', 'unit_models.id', '=', 'vehicle_units.model_id')
                    ->whereBetween('datetime_out', [$datefrom, $dateto])
                    ->where('shop_id','=',$sp->id)
                    ->where('unit_models.id','=',$md->id)
                    ->value('unit_movements.std_hrs');
                $modelstdhrs[$sp->id][$md->id] = $hrspmd;
                $tthrspmd += $hrspmd*$mtd;
                $ttstdhours += $hrspmd*$mtd;
            }
        }else{
            $tthrspmd = 0;
            $MTDmodelcount[$sp->id][1] = 0; $modelstdhrs[$sp->id][1] = 0;

        }

        $shopmodelhrs[$sp->id] = round($tthrspmd,2);

            //MONTH TO DATE EFFICIENCY
            $mtddrhrs = Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_id','=',$sp->id)->sum(DB::raw('efficiencyhrs'));
                $mtdlnhrs = Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to','=',$sp->id)->sum(DB::raw('loaned_hrs'));
                $mtdotlnhrs = Attendance::whereBetween('date', [$datefrom, $dateto])->where('otshop_loaned_to','=',$sp->id)->sum(DB::raw('otloaned_hrs'));
            $MTDinput = $mtddrhrs + $mtdlnhrs + $mtdotlnhrs;

            $MTDoutput = Unitmovement::whereBetween('datetime_out', [$datefrom, $dateto])
                                ->where('shop_id','=',$sp->id)->sum('std_hrs');
            $MTDshop_eff[$sp->id] = ($MTDinput > 0) ? round(($MTDoutput/$MTDinput)*100,2) : 0;

        }

        //EFFPLANT MTD
        $ppmtddrhrs = 0; $ppmtdlnhrs = 0; $ppmtdotlnhrs = 0;
        foreach($shops as $shop){
            $ppmtddrhrs += Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_id',$shop->id)->sum(DB::raw('efficiencyhrs'));
            $ppmtdlnhrs += Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to',$shop->id)->sum(DB::raw('loaned_hrs'));
            $ppmtdotlnhrs += Attendance::whereBetween('date', [$datefrom, $dateto])->where('shop_loaned_to',$shop->id)->sum(DB::raw('otloaned_hrs'));
        }
        $ppMTDinput = $ppmtddrhrs + $ppmtdlnhrs + $ppmtdotlnhrs;
        $ppMTDoutput = Unitmovement::whereBetween('datetime_out', [$datefrom, $dateto])->sum('std_hrs');
        $pplcvfinal = Unitmovement::whereBetween('datetime_out', [$datefrom, $dateto])->where('shop_id',13)->sum('std_hrs');
        $ppMTDoutput -= $pplcvfinal;
        $ppMTDplant_eff = ($ppMTDinput > 0) ? round(($ppMTDoutput/$ppMTDinput)*100,2) : 0;

        $range = Carbon::createFromFormat('Y-m-d', $datefrom)->format('jS M Y').' To '.Carbon::createFromFormat('Y-m-d', $dateto)->format('jS M Y');
        //return $shopmodelhrs;
        $data = array(
            'shops'=>$shops,
            'model'=>$model,
            'MTDmodelcount'=>$MTDmodelcount,

            'modelstdhrs'=>$modelstdhrs,
            'shopmodelhrs'=>$shopmodelhrs,

            'MTDshop_eff'=>$MTDshop_eff,
            'ttstdhours'=>$ttstdhours,
            'ppMTDplant_eff'=>$ppMTDplant_eff,

            'rowspan'=> $rowspan,

            'fromdate'=>$datefrom,
            'todate'=>$dateto,
            'range'=>$range,

        );
        return view('attendances.prodnoutput')->with($data);
    }



    public function weeklystdhrs(Request $request){
        //return $request->input('mdate');
        if($request->input()){
            $date = $request->input('mdate');
            $todate = Carbon::createFromFormat('m/d/Y',$date)->format('Y-m-d');
        }else{
            $todate = Carbon::today()->format('Y-m-d');
        }


        $shops = Shop::where('check_shop','=','1')->get(['id','report_name']);
        unset($shops[9]);
        //STANDARD HOURS GENERATED AND ACTUAL HOURS
        foreach($shops as $sp){
            $weekStartDate = Carbon::parse($todate)->startOfWeek()->format('Y-m-d');
            $weekEndDate = Carbon::parse($todate)->endOfWeek()->format('Y-m-d');

            while($weekStartDate <= $weekEndDate){
                $dates[] = Carbon::createFromFormat('Y-m-d', $weekStartDate)->format('jS');
                $date = $weekStartDate;

               $act = Attendance::where('date','=', $weekStartDate)->where('shop_id','=',$sp->id)
                                ->sum(DB::raw('efficiencyhrs'));
                    $lnhrs = Attendance::where([['date','=', $weekStartDate],['shop_loaned_to','=',$sp->id]])->sum(DB::raw('loaned_hrs'));
                    $otlnhrs = Attendance::where([['date','=', $weekStartDate],['otshop_loaned_to','=',$sp->id]])->sum(DB::raw('otloaned_hrs'));
                $actual = $act + $lnhrs + $otlnhrs;

                $actualhrs[$sp->id][] = ($actual > 0) ? round($actual,2) : '--';
                $std = Unitmovement::where('datetime_out','=', $weekStartDate)
                                    ->where('shop_id','=',$sp->id)->sum('std_hrs');
                $stdhrs[$sp->id][] = ($std > 0) ? round($std,2) : '--';

                $weekStartDate = Carbon::parse($date)->addDays()->format('Y-m-d');
            }

            $wkStartDate = Carbon::parse($todate)->startOfWeek()->format('Y-m-d');
            $wkEndDate = Carbon::parse($todate)->endOfWeek()->format('Y-m-d');
                $wkact = Attendance::whereBetween('date', [$wkStartDate, $wkEndDate])->where('shop_id','=',$sp->id)
                                ->sum(DB::raw('efficiencyhrs'));
                    $wklnhrs = Attendance::whereBetween('date', [$wkStartDate, $wkEndDate])->where('shop_loaned_to','=',$sp->id)->sum(DB::raw('loaned_hrs'));
                    $wkotlnhrs = Attendance::whereBetween('date', [$wkStartDate, $wkEndDate])->where('otshop_loaned_to','=',$sp->id)->sum(DB::raw('otloaned_hrs'));
                $wkactual = $wkact + $wklnhrs + $wkotlnhrs;

                $weekactualhrs[$sp->id] = ($wkactual > 0) ? round($wkactual,2) : '--';
                $wkstd = Unitmovement::whereBetween('datetime_out', [$wkStartDate, $wkEndDate])
                                    ->where('shop_id','=',$sp->id)->sum('std_hrs');
                $weekstdhrs[$sp->id] = ($wkstd > 0) ? round($wkstd,2) : '--';

        }

        //return $actualhrs;

        $data = array(
            'todate'=>$todate,
            'shops'=>$shops,
            'dates'=>$dates,
            'actualhrs'=>$actualhrs,
            'stdhrs'=>$stdhrs,
            'weekactualhrs'=>$weekactualhrs,
            'weekstdhrs'=>$weekstdhrs,
        );
        return view('attendances.weeklystdhrs')->with($data);
    }


    public function weeklyactualhrs(){
        $todate = Carbon::today()->format('Y-m-d');
        $filday = array('DAY','WTD','MTD');
        $shops = Shop::where('check_shop','=','1')->get(['id','report_name']);
        $data = array(
            'todate'=>$todate,
            'shops'=>$shops,
            'filday'=>$filday,
        );
        return view('attendances.weeklyactualhrs')->with($data);
    }

     public function peopleAttreport(Request $request){
        if($request->input()){
            $activetag = $request->input('rangeid');
            $date = $request->input('mdate');
        }else{
            $activetag = IndivTarget::max('id');
        }

        $shops = Shop::where('check_shop','=',1)->get(['id','report_name']);
        unset($shops[9]);


        $today = Carbon::today()->format('Y-m-d');

        $today = ($request->input()) ? Carbon::createFromFormat('m/d/Y', $request->input('mdate'))->format('Y-m-d') : $today;

        //DAILY EFFICIENCY
        $TTinput = 0; $TToutput = 0;

        foreach($shops as $shop){
            $inpp = Attendance::where([['date','=',$today],['shop_id','=',$shop->id]])->sum(DB::raw('efficiencyhrs'));
                $lnhrs = Attendance::where([['date','=',$today],['shop_loaned_to','=',$shop->id]])->sum(DB::raw('loaned_hrs'));
                $otlnhrs = Attendance::where([['date','=',$today],['otshop_loaned_to','=',$shop->id]])->sum(DB::raw('otloaned_hrs'));
            $input = $inpp + $lnhrs + $otlnhrs;
            $TTinput += $input;

            $output = Unitmovement::where('datetime_out','=',$today)
                    ->where('shop_id','=',$shop->id)->sum('std_hrs');
            $TToutput += $output;
            $shop_eff[$shop->id] = ($input > 0) ? round(($output/$input)*100,2).'%' : '--';


        //ABSENTIEESM
        $empcount = Attendance::where([['date', '=', $today], ['shop_id', '=', $shop->id]])->count();
        if($empcount != null){
            $expectedhrs = $empcount * 8;
            $hrsworked = Attendance::Where([['date', '=', $today],['shop_id', '=', $shop->id]])
                            ->sum(DB::raw('direct_hrs + indirect_hrs'));
            $absent = $expectedhrs - $hrsworked;
            ($absent > 0) ? $absentiesm[$shop->id] = round(((($absent)/$expectedhrs)*100),2).'%' : $absentiesm[$shop->id] = 0;
        }else{
            $absentiesm[$shop->id] = '--';
        }


        //TEAMLEADER AVAILABILITY
        $direct = 0; $indirect = 0; $tthrs = 0;
        $teamleaders = Employee::where([['team_leader','=','yes'],['shop_id', '=', $shop->id],['status','=','Active']])->get('id');
        foreach($teamleaders as $tl){
            $direct += Attendance::where([['staff_id','=',$tl->id],['date', '=', $today]])
                        ->sum(DB::raw('direct_hrs + othours'));
            $indirect += Attendance::where([['staff_id','=',$tl->id],['date', '=', $today]])
                        ->sum(DB::raw('indirect_hrs + indirect_othours'));
        }
        $tthrs = $indirect+$direct;

        $shopTLavail[$shop->id] = ($tthrs > 0) ? round(($indirect/$tthrs)*100,2).'%' : '--';

        // TL TARGETS
        $shopsTLtarget[$shop->id] = round(getshopTLAtarget($shop->id),2);

    }


        $eff_abTargets = getTarget($today);

        $data = array(
            'abT'=>getTarget($today)->absentieesm,
            'effT'=>getTarget($today)->efficiency,
            'shopsTLtarget'=>$shopsTLtarget, 'shops'=>$shops,
            'shop_eff'=>$shop_eff,
            'absentiesm'=>$absentiesm,
            'shopTLavail'=>$shopTLavail,
            'today'=>$today,
        );
        return view('attendances.peopleAttreport')->with($data);
    }


    public function settargets(request $request){

        $targets =  IndivTarget::All();

        $data = array(
            'targets'=>$targets,

        );
        return view('attendances.settargets')->with($data);
    }

    public function createtargets(){
        //return getTarget("2022-01-02")->efficiency;
        $shops = Shop::where('check_shop','=','1')->get(['id','report_name']);
        $year = Carbon::today()->format('Y');
        $years = [$year-1,$year,$year+1];
        $quarters = ['First Quarter','Second Quarter','Third Quarter','Fourth Quarter'];

        $year = Carbon::today()->format('Y');
        $thisyeartargets =  IndivTarget::where('year',$year)->get();

        $data = array(
            'shops'=>$shops,
            'years'=>$years,
            'quarters'=>$quarters,

            'year'=>$year,
            'thisyeartargets'=>$thisyeartargets,
        );
        return view('attendances.createtargets')->with($data);
    }

    public function savetargets(Request $request){

        $validator = Validator::make($request->all(), [
            'yearquarter' => 'required',
            'pefficiency' => 'required',
            'pabsentieesm' => 'required',
            'ptlavailability' => 'required',

        ]);

        if ($validator->fails()) {
            Toastr::error('Sorry! All fields are required.');
            return back();
        }

        $yearquarter = $request->input('yearquarter');
         $dataarr = explode('-',$yearquarter);
         $year = $dataarr[0];
         $qtno = $dataarr[1];

        $efficiency = $request->input('efficiency');
        $absentieesm = $request->input('absentieesm');
        $tlavailability = 0;//$request->input('tlavailability');

        $quarts = [1=>'1st Quarter',2=>'2nd Quarter',3=>'3rd Quarter',4=>'4th Quarter'];

        $quarter = $quarts[$qtno];

        try{
            DB::beginTransaction();

            $tgt = IndivTarget::where([['yearquarter',$quarter],['year',$year]])->first();
            if(!isset($tgt)){
                $tgt = new IndivTarget;
            }


            $tgt->year = $year;
            $tgt->yearquarter = $quarter;
            $tgt->efficiency = $request->input('pefficiency');
            $tgt->absentieesm = $request->input('pabsentieesm');
            $tgt->tlavailability = $request->input('ptlavailability');

            $tgt->user_id = auth()->user()->id;
            $tgt->save();


            DB::commit();
            Toastr::success('Targets saved successfully','Saved');
            return back();
    }
    catch(\Exception $e){
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
        DB::rollback();
        Toastr::error('An error occured, Targets not saved.','Whoops!');
            return back();
    }
}



public function destroytag($id){
    if (request()->ajax()) {
        try {
            $can_be_deleted = true;
            $error_msg = '';

            //Check if any routing has been done
           //do logic here
           $tag = IndivTarget::where('id', $id)->first();

            if ($can_be_deleted) {
                if (!empty($tag)) {
                    DB::beginTransaction();
                    //Delete Query  details
                    IndivTarget::where('id', $id)->delete();
                    $tag->delete();
                    DB::commit();

                    $output = ['success' => true,
                            'msg' => "Target Deleted Successfully"
                        ];
                }else{
                    $output = ['success' => false,
                            'msg' => "Could not be deleted, Child record exist."
                        ];
                }
            } else {
                $output = ['success' => false,
                            'msg' => $error_msg
                        ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => false,
                            'msg' => "Something Went Wrong"
                        ];
        }
        return $output;
    }
}

public function reportsummary(){
    $today = Carbon::today()->format('Y-m-d');
    $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
    $firstthisyear = Carbon::create(Carbon::now()->year,1,1)->format('Y-m-d');

    //CUMMULATIVE HOURS WORKED
    $cumhrsyear = Attendance::whereBetween('date', [$firstthisyear, $today])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
    //CUMMULATIVE HOURS GENERATED
    $cumstdhrsyear = Unitmovement::whereBetween('datetime_out', [$firstthisyear, $today])
                                ->sum('std_hrs');
    //CUMMYLATIVE INDIRECT HOURS
    $cumindirecthrsyear = Attendance::whereBetween('date', [$firstthisyear, $today])
                        ->sum(DB::raw('indirect_hrs '));
    //MONTH TO DATE EFFICIENCY
    $YTDinput = Attendance::whereBetween('date', [$firstthisyear, $today])
                ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
    $YTDoutput = Unitmovement::whereBetween('datetime_out', [$firstthisyear, $today])
                ->sum('std_hrs');
    $YTDcumeff = ($YTDinput > 0) ? round(($YTDoutput/$YTDinput)*100,2) : 0;
    //return $YTDcumeff;

    $data = array(
        'cumhrsyear'=>$cumhrsyear,
        'cumstdhrsyear'=>$cumstdhrsyear,
        'cumindirecthrsyear'=>$cumindirecthrsyear,
        'YTDcumeff'=>$YTDcumeff,
    );
    return view('attendances.reportsummary')->with($data);
}



public function yestreportsummary(){
    $yesterday = Carbon::yesterday()->format('Y-m-d');
    $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
    $firstthisyear = Carbon::create(Carbon::now()->year,1,1)->format('Y-m-d');

    //CUMMULATIVE HOURS WORKED
    $cumhrsyear = Attendance::whereBetween('date', [$firstthisyear, $yesterday])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
    //CUMMULATIVE HOURS GENERATED
    $cumstdhrsyear = Unitmovement::whereBetween('datetime_out', [$firstthisyear, $yesterday])
                                ->sum('std_hrs');
    //CUMMYLATIVE INDIRECT HOURS
    $cumindirecthrsyear = Attendance::whereBetween('date', [$firstthisyear, $yesterday])
                        ->sum(DB::raw('indirect_hrs '));

    $data = array(
        'cumhrsyear'=>$cumhrsyear,
        'cumstdhrsyear'=>$cumstdhrsyear,
        'cumindirecthrsyear'=>$cumindirecthrsyear,

    );
    return view('attendances.yestreportsummary')->with($data);
}

public function plantattendancereg(Request $request){

    $shops = Shop::where('overtime','=','1')->get(['id','report_name']);

    foreach($shops as $sp){

        if($request->input()){
            $date = $request->input('mdate');
           $date1 = Carbon::createFromFormat('F Y', $date)->format('Y-m-d');
           $date = Carbon::createFromFormat('Y-m-d', $date1);
           $firstthismonth = $date->startOfMonth()->toDateString();
           $endthismonth = $date->endOfMonth()->toDateString();
           $today = $endthismonth;
       }else{
           $today = Carbon::today()->format('Y-m-d');
           $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
       }
       //return $firstthismonth;
       $allschdates = Production_target::whereBetween('date', [$firstthismonth, $today])
                        ->groupby('date')->get(['date']);
       //vehicle_units::whereBetween('offline_date', [$firstthismonth, $today])
                            //->groupby('offline_date')->get(['offline_date']);

        if(count($allschdates) == 0){
            Toastr::error('Sorry, There is no schedules for the month.','Whoops!');
            return back();
        }
       $schdates = [];
        foreach($allschdates as $schdt){
            $schdates[] = $schdt->date;
        }


        for($n = 0; $n < count($schdates); $n++){
        //while($firstthismonth <= $today){
            $dates[] = Carbon::createFromFormat('Y-m-d', $schdates[$n])->format('jS');
            $date = $firstthismonth;

                $mked = Attendance::where([['date', '=', $schdates[$n]], ['shop_id', '=',$sp->id]])->first();
                $marked[$sp->id][] = (!empty($mked)) ? 1 : 0;
        }
    }

    $data = array(
        'today'=>$today,
        'dates'=>$dates,
        'count'=>count($schdates),
        'marked'=>$marked,
        'shops'=>$shops,
    );
    return view('attendances.plantattendancereg')->with($data);
}

public function attendceregister(Request $request)
{
    if($request->input()){
        $date = $request->input('mdate');
       $date1 = Carbon::createFromFormat('F Y', $date)->format('Y-m-d');
       $date = Carbon::createFromFormat('Y-m-d', $date1);
       $firstthismonth = $date->startOfMonth()->toDateString();
       $endthismonth = $date->endOfMonth()->toDateString();
       $today = $endthismonth;
       $shopid = $request->input('shop');
   }else{
       $today = Carbon::today()->format('Y-m-d');
       $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
       if(shop() == "noshop"){
            $shopid = 1;
        }else{
            $shopid = Auth()->User()->section;
        }
   }

   //PRODUCTION DAYS
   $allschdates = Production_target::whereBetween('date', [$firstthismonth, $today])
                        ->groupby('date')->get(['date']);

   //vehicle_units::whereBetween('offline_date', [$firstthismonth, $today])
            //->groupby('offline_date')->get(['offline_date']);
        if(count($allschdates) == 0){
            Toastr::error('Sorry, There is no schedules for the month.','Whoops!');
            return back();
            }
        foreach($allschdates as $schdt){  $prodndays[] = $schdt->date; }

   $shopname = Shop::where('id','=',$shopid)->value('report_name');

   $employees = Employee::where('shop_id','=',$shopid)->get(['id','staff_no','staff_name','team_leader']);
   foreach($employees as $emp){

        $tthrs = 0;
        for($n = 0; $n < count($prodndays); $n++){
            $dates[] = Carbon::createFromFormat('Y-m-d', $prodndays[$n])->format('jS');

            $hours = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
            ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $emphrs[$emp->id][] = $hours; $tthrs += $hours;
        }
        $ttemphrs[$emp->id] = $tthrs;
   }

   //Totals
    $totalhr = 0;
    for($n = 0; $n < count($prodndays); $n++){
        $allemp = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid]])->count();
        $count = 0; $availhr = 0;
        foreach($employees as $emp){
        $hrss = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $count = ($hrss > 0) ? $count += 1 : $count;

        //TEAMLEADER AVAILABILITY
        if($emp->team_leader == 'yes'){
            $indirect = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('indirect_hrs'));
            $totalhr = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
        }

        $availhr = ($totalhr > 0) ? round(($indirect/$totalhr)*100,2) : 0;
        }
         $ttpresent[] = $count;
         $ttemp[] = $allemp;
         $tlavail[] = $availhr;
    }

    //return $tlavail;

   //Per date
   $tthrs = 0; $ttsum = 0;
        for($n = 0; $n < count($prodndays); $n++){
            $tthh = Attendance::where([['date','=',$prodndays[$n]],['shop_id','=',$shopid]])
                ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $ttsum += $tthh;  $hrsperdate[] = $tthh;


        }

   //return $emphrs;
    $data = array(
        'shopid'=>$shopid,
        'employees' => $employees, 'ttpresent'=>$ttpresent,
        'today'=>$today, 'ttemp'=>$ttemp,'tlavail'=>$tlavail,
        'dates'=>$dates,
        'count'=>count($prodndays),
        'emphrs'=>$emphrs, 'ttemphrs'=>$ttemphrs, 'hrsperdate'=>$hrsperdate, 'ttsum'=>$ttsum,
        'shopname'=>$shopname,
        'selectshops'=>Shop::where('overtime','=',1)->get(['report_name','id']),
    );
    return view('attendances.attendceregister')->with($data);
}


public function exportattendRegister(Request $request){
    if($request->input()){
       $date = $request->input('mdate');
       $date1 = Carbon::createFromFormat('F Y', $date)->format('Y-m-d');
       $date = Carbon::createFromFormat('Y-m-d', $date1);
       $firstthismonth = $date->startOfMonth()->toDateString();
       $endthismonth = $date->endOfMonth()->toDateString();
       $today = $endthismonth;
       $shopid = $request->input('shop');
   }else{
       $today = Carbon::today()->format('Y-m-d');
       $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
       $shopid = 1;
   }

   //PRODUCTION DAYS
   $allschdates = Production_target::whereBetween('date', [$firstthismonth, $today])
                        ->groupby('date')->get(['date']);

        if(count($allschdates) == 0){
            Toastr::error('Sorry, There is no schedules for the month.','Whoops!');
            return back();
            }
        foreach($allschdates as $schdt){  $prodndays[] = $schdt->date; }

   $shopname = Shop::where('id','=',$shopid)->value('report_name');

   $employees = Employee::where('shop_id','=',$shopid)->get(['id','staff_no','staff_name','team_leader']);
   foreach($employees as $emp){

        $tthrs = 0;
        for($n = 0; $n < count($prodndays); $n++){
            $dates[] = Carbon::createFromFormat('Y-m-d', $prodndays[$n])->format('jS');

            $hours = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
            ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $emphrs[$emp->id][] = $hours; $tthrs += $hours;
        }
        $ttemphrs[$emp->id] = $tthrs;
   }

   //Totals
    $totalhr = 0;
    for($n = 0; $n < count($prodndays); $n++){
        $allemp = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid]])->count();
        $count = 0; $availhr = 0;
        foreach($employees as $emp){
        $hrss = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $count = ($hrss > 0) ? $count += 1 : $count;

        //TEAMLEADER AVAILABILITY
        if($emp->team_leader == 'yes'){
            $indirect = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('indirect_hrs'));
            $totalhr = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
        }

        $availhr = ($totalhr > 0) ? round(($indirect/$totalhr)*100,2) : 0;
        }
         $ttpresent[] = $count;
         $ttemp[] = $allemp;
         $tlavail[] = $availhr;
    }

    //return $tlavail;

   //Per date
   $tthrs = 0; $ttsum = 0;
        for($n = 0; $n < count($prodndays); $n++){
            $tthh = Attendance::where([['date','=',$prodndays[$n]],['shop_id','=',$shopid]])
                ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $ttsum += $tthh;  $hrsperdate[] = $tthh;


        }

   //return $emphrs;
    $data = array(
        'shopid'=>$shopid,
        'employees' => $employees, 'ttpresent'=>$ttpresent,
        'today'=>$today, 'ttemp'=>$ttemp,'tlavail'=>$tlavail,
        'dates'=>$dates,
        'count'=>count($prodndays),
        'emphrs'=>$emphrs, 'ttemphrs'=>$ttemphrs, 'hrsperdate'=>$hrsperdate, 'ttsum'=>$ttsum,
        'shopname'=>$shopname,
        'selectshops'=>Shop::where('overtime','=',1)->get(['report_name','id']),
    );
    return Excel::download(new AttndRegisterView($data), 'register.xlsx');
}


//PDF
public function attendRegisterpdf(Request $request){
    if($request->input()){
        $date = $request->input('mdate');
       $date1 = Carbon::createFromFormat('F Y', $date)->format('Y-m-d');
       $date = Carbon::createFromFormat('Y-m-d', $date1);
       $firstthismonth = $date->startOfMonth()->toDateString();
       $endthismonth = $date->endOfMonth()->toDateString();
       $today = $endthismonth;
       $shopid = $request->input('shop');
   }else{
       $today = Carbon::today()->format('Y-m-d');
       $firstthismonth = Carbon::now()->startOfMonth()->format('Y-m-d');
       $shopid = 1;
   }

   //PRODUCTION DAYS
   $allschdates = Production_target::whereBetween('date', [$firstthismonth, $today])
                        ->groupby('date')->get(['date']);

        if(count($allschdates) == 0){
            Toastr::error('Sorry, There is no schedules for the month.','Whoops!');
            return back();
            }
        foreach($allschdates as $schdt){  $prodndays[] = $schdt->date; }

   $shopname = Shop::where('id','=',$shopid)->value('report_name');

   $employees = Employee::where('shop_id','=',$shopid)->get(['id','staff_no','staff_name','team_leader']);
   foreach($employees as $emp){

        $tthrs = 0;
        for($n = 0; $n < count($prodndays); $n++){
            $dates[] = Carbon::createFromFormat('Y-m-d', $prodndays[$n])->format('jS');

            $hours = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
            ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $emphrs[$emp->id][] = $hours; $tthrs += $hours;
        }
        $ttemphrs[$emp->id] = $tthrs;
   }

   //Totals
    $totalhr = 0;
    for($n = 0; $n < count($prodndays); $n++){
        $allemp = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid]])->count();
        $count = 0; $availhr = 0;
        foreach($employees as $emp){
        $hrss = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $count = ($hrss > 0) ? $count += 1 : $count;

        //TEAMLEADER AVAILABILITY
        if($emp->team_leader == 'yes'){
            $indirect = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('indirect_hrs'));
            $totalhr = Attendance::where([['date','=', $prodndays[$n]],['shop_id','=',$shopid],['staff_id','=',$emp->id]])
                        ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
        }

        $availhr = ($totalhr > 0) ? round(($indirect/$totalhr)*100,2) : 0;
        }
         $ttpresent[] = $count;
         $ttemp[] = $allemp;
         $tlavail[] = $availhr;
    }

    //return $tlavail;

   //Per date
   $tthrs = 0; $ttsum = 0;
        for($n = 0; $n < count($prodndays); $n++){
            $tthh = Attendance::where([['date','=',$prodndays[$n]],['shop_id','=',$shopid]])
                ->sum(DB::raw('direct_hrs + indirect_hrs + loaned_hrs'));
            $ttsum += $tthh;  $hrsperdate[] = $tthh;


        }

   //return $emphrs;
    $data = array(
        'employees' => $employees, 'ttpresent'=>$ttpresent,
        'today'=>$today, 'ttemp'=>$ttemp,'tlavail'=>$tlavail,
        'dates'=>$dates,
        'count'=>count($prodndays),
        'emphrs'=>$emphrs, 'ttemphrs'=>$ttemphrs, 'hrsperdate'=>$hrsperdate, 'ttsum'=>$ttsum,
        'shopname'=>$shopname,
        'selectshops'=>Shop::where('overtime','=',1)->get(['report_name','id']),
    );
    $pdfdata = PDF::loadview('attendances.attendanceregister_table',$data, [], [
        'orientation' => 'L',
        'default_font_size' => '14',
      ]);
       return $pdfdata->download('attendanceregister.pdf');
}
}
