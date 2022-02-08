<?php

namespace App\Http\Controllers\attendancepreview;

//use App\Models\attendancepreview\AttendancePreview;
use App\Models\attendancepreview\Attendance_remarks;

use App\Models\attendancestatus\Attendance_status;
use App\Models\reviewconversation\Review_conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\employee\Employee;
use App\Models\shop\Shop;
use App\Models\attendance\Attendance;
use App\Models\productiontarget\Production_target;
use App\Models\workschedule\WorkSchedule;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;

class AttendancePreviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        $shops = Shop::where('overtime','=','1')->get(['id','report_name']);
        $selectshops = Shop::pluck('report_name','id');
        foreach($shops as $sp){
            $names[] = $sp->report_name;

            $check1 = Attendance_status::where([['date', '=', $today], ['shop_id', '=', $sp->id]])->value('status_name');
            $checky1 = Attendance_status::where([['date', '=', $yesterday], ['shop_id', '=', $sp->id]])->value('status_name');
            $confirmedtoday[] = ($check1 == "approved") ? "check" : "";
            $confirmedyesterday[] = ($checky1 == "approved") ? "check" : "";


            $check = Attendance_status::where([['date', '=', $today], ['shop_id', '=', $sp->id]])->value('status_name');
            $checky = Attendance_status::where([['date', '=', $yesterday], ['shop_id', '=', $sp->id]])->value('status_name');

            $colord[] = ($check == "" || $check == 'saved' || $check == "reveiw") ? "success" : "warning";
            $colory[] = ($checky == "" || $checky == 'saved' || $checky == "reveiw") ? "success" : "warning";

            $count_TT[] = Employee::where([['shop_id', '=', $sp->id],['status','=','Active']])->count('id');

            $empids = Employee::where([['shop_id','=',$sp->id],['status','=','Active']])->get('id');
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
        $usershopid = (Auth()->User()->section == 'ALL') ? 0 : Auth()->User()->section;
        $proddayys = Attendance::groupBy('date')->whereBetween('date',['2021-11-23',Carbon::today()->format('Y-m-d')])
                    ->where('shop_id','=',$usershopid)->get('date');
        $unlogged = []; $submitted = []; $reviews = [];
        foreach($proddayys as $dayy){
            $logged = Attendance_status::where([['date','=',$dayy->date],['shop_id','=',Auth()->User()->section]])->first();
            if($logged == ""){
                $unlogged[] = Carbon::createFromFormat('Y-m-d', $dayy->date)->format('d M Y');
            }

            $submit = Attendance_status::where([['date','=',$dayy->date],['shop_id','=',Auth()->User()->section]])->value('status_name');
            if($submit == "submitted"){
                $submitted[] = Carbon::createFromFormat('Y-m-d', $dayy->date)->format('d M Y');
            }

            $review = Attendance_status::where([['date','=',$dayy->date],['shop_id','=',Auth()->User()->section]])->value('status_name');
            if($review == "review"){
                $reviews[] = Carbon::createFromFormat('Y-m-d', $dayy->date)->format('d M Y');
            }
        }

        //return $submitted;

        $data = array(
            'shops' => Shop::where('overtime','=','1')->pluck('report_name','id'),
            'unlogged'=>$unlogged, 'submitted'=>$submitted, 'reviews'=>$reviews,
            'names' =>$names,
            'colord'=>$colord,
            'colory'=>$colory,
            'count_TT'=>$count_TT,
            'count_presenttoday'=>$count_presenttoday,
            'count_presentyesterday'=>$count_presentyesterday,
            'confirmedtoday'=>$confirmedtoday,
            'confirmedyesterday'=>$confirmedyesterday,


        );
       return view('attendancepreview.attendance_view')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkattendance(Request $request)
    {
            $mdate =$request->input('mdate');
            $shopid = $request->input('shop');
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

        //CHECK LOANEES
        $loanee = Attendance::where([['date', '=', $date], ['otshop_loaned_to', '=', $shopid]])->first();
        $check = Attendance::where([['loan_confirm', '=', 1],['date', '=', $date], ['otshop_loaned_to', '=', $shopid]])
                                ->first();

        //return $date;
       $marked = Attendance::where([['date', '=', $date], ['shop_id', '=', $shopid]])->first();
        if($marked != null){
            //$shopname = Shop::where('id', $shopid)->value('shop_name');
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
            $indirectshop = Shop::where('id','=',$shopid)->value('check_shop');
            //SUBMISSION STATUS
            $attstatus = Attendance_status::where([['shop_id','=',$shopid],['date','=',$date]])->first();
                if($attstatus == "" || $attstatus->status_name == "saved"){
                    Toastr::error('Sorry! Attendance Not Yet Marked','Not Marked');
                    return back();
                }

            //CONVERSATION
            $statusid = Attendance_status::where([['shop_id','=',$shopid],['date','=',$date]])->value('id');
            if(!empty($statusid)){
                $conversation = Review_conversation::where('statusid','=',$statusid)
                                ->get(['user_id','statusid','sender','message','created_at']);
            }

            $date = Carbon::createFromFormat('m/d/Y', $mdate)->format('Y-m-d');

                 $staffs = Attendance::where([['date', '=', $date], ['shop_id', '=', $shopid]])->get();

                    $confirm = Attendance_status::where([['date', '=', $date], ['shop_id', '=', $shopid]])->first();

                    $icon = $confirm ? 'check' : 'window-minimize';
                    $color = $confirm ? 'warning' : 'danger';
                    $disabled = $confirm ? 'disabled' : 'enabled';
                    $text = $confirm ? 'Attendance Confirmed' : 'Confirm Attendance';


                    $data = array(
                        'num' => 1, 'i'=>0, 'dayname'=>$dayname, 'attstatus'=>$attstatus,
                        'staffs'=>$staffs,'text'=>$text, 'prodday'=>$prodday,
                        'icon'=>$icon,'color'=>$color,'disabled'=>$disabled,
                        'shop' => $shopname,'conversation'=>$conversation,
                        'shopid' => $shopid,'loanee'=>$loanee,
                        'shops' => $allshops,'test'=>'testing',
                        'date' => $date, 'indirectshop'=>$indirectshop,
                        'btncolor' => 'warning', 'btntext' => 'Update',
                        'color1'=>($check) ? 'success' : 'danger',
                        'text1'=>($check) ? 'View Loaned' : 'Approve Loaned',
                        'icon1'=>($check) ? 'check' : 'window-minimize',
                    );
                    return view('attendancepreview.index')->with($data);


        }else{
            Toastr::error('Sorry! Attendance Not Yet Marked','Not Marked');
            return back();
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function confirmattendance(Request $request)
    {
        $preview = new Attendance_status;
        $date =  $request->input('date');
        $shopid = $request->input('shopid');
        $confirmed = Attendance_status::where([['date', '=', $date], ['shop_id', '=', $shopid]])->first();
        if($confirmed == null){
            $preview->date = $date;
            $preview->shop_id = $shopid;
            $preview->user_id = auth()->user()->id;
            $preview->save();
        }

        Toastr::success('Attendance confirmed successfully','Confirmed');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AttendancePreview  $attendancePreview
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = $request->input('date');
        $shop_id = $request->shop_id;

    //ATTENDANCE STATUS
    if($request->input('button') == "approved"){
        try{
            DB::beginTransaction();
            $statusid = Attendance_status::where([['shop_id','=',$shop_id],['date','=',$date]])->value('id');
            $status = Attendance_status::find($statusid);
            $status->status_name = $request->input('button');
            $status->save();
        DB::commit();
            Toastr::success('Attendance Approved successfully','Approved');
            return back();
        }
        catch(\Exception $e){
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            Toastr::error('Error occured, approval failed!','Error');
            return  $e->getMessage();
        }
    }


        $staffid = $request->staff_id;
        $direct = $request->direct;
        $indirect = $request->indirect;


        $workdescription = $request->workdescription;
        $date = $request->input('date');

        $overshoptoid = $request->overshoptoid;
        $loanov = $request->loanov;

        $shop_id = $request->shop_id;
        $dirshopto_id = $request->dirshopto;
        $loandir = $request->loandir;

            $markedid = Attendance::where([['date', '=', $date], ['shop_id', '=', $shop_id]])->get('id');//->first();

            try{
            DB::beginTransaction();
            for($i = 0; $i < count($staffid); $i++)
            {
                $attend = Attendance::find($markedid[$i]->id);

                    $attend->staff_id = $staffid[$i];
                    $attend->direct_hrs = $direct[$i];
                    $attend->indirect_hrs = $indirect[$i];

                    $attend->otshop_loaned_to = $overshoptoid[$i];
                    $attend->otloaned_hrs = $loanov[$i];

                    $attend->shop_loaned_to = $dirshopto_id[$i];
                    $attend->loaned_hrs = $loandir[$i];

                    $attend->workdescription = $workdescription[$i];

                    $hours = Attendance::Where('id', '=', $markedid[$i]->id)
                                    ->sum(DB::raw('othours + indirect_othours'));
                    $attend->efficiencyhrs = (($direct[$i] + $indirect[$i]) * 0.97875) + $hours;

                    $attend->save();
                }


            //ATTENDANCE STATUS
            $statusid = Attendance_status::where([['shop_id','=',$shop_id],['date','=',$date]])->value('id');

            $status = Attendance_status::find($statusid);
            $status->workdescription = $request->input('workdescriptionall');
            $status->save();

            DB::commit();
            Toastr::success('Attendance updated successfully','Updated');
            return back();
        }
        catch(\Exception $e){
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            Toastr::error('Error occured, update failed!','Error');
            return  $e->getMessage();
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AttendancePreview  $attendancePreview
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendancePreview $attendancePreview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendancePreview  $attendancePreview
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttendancePreview $attendancePreview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AttendancePreview  $attendancePreview
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendancePreview $attendancePreview)
    {
        //
    }
}
