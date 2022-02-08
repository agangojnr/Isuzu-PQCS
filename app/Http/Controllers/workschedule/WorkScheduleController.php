<?php

namespace App\Http\Controllers\workschedule;

use App\Models\workschedule\WorkSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class WorkScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holis = WorkSchedule::all()->sortDesc();

        $data = array(
            'holis' =>$holis,
        );
        return view('work_schedule.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('work_schedule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mdate' => 'required|unique:work_schedules,date',
            'holidayname' => 'required',

        ]);
        // Check validation failure
        if ($validator->fails()) {
            Toastr::error('Sorry! Holiday should be unique.');
            return back();
        }
        $hdate = Carbon::createFromFormat('m/d/Y', $request->input('mdate'))->format('Y-m-d');
        $unique = WorkSchedule::where('date','=',$hdate)->first();
        if(!empty($unique)){
            Toastr::error('Sorry! Holiday date must be unique.');
            return back();
        }

        try{
            DB::beginTransaction();
            $holi = new WorkSchedule;
            $holi->holidayname = $request->input('holidayname');
            $holi->date = $hdate;
            $holi->user_id = auth()->user()->id;
            $holi->save();

            DB::commit();
            Toastr::success('Holiday saved successfully.','Confirmed!');
            return back();
        }
        catch(\Exception $e){
            DB::rollback();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            Toastr::error($e->getMessage());
            //Toastr::error('Sorry, An error occured.','Whooops!');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkSchedule  $workSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(WorkSchedule $workSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkSchedule  $workSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkSchedule $workSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkSchedule  $workSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkSchedule $workSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkSchedule  $workSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkSchedule $workSchedule, $id)
    {
        if (request()->ajax()) {
            try {
                $can_be_deleted = true;
                $error_msg = '';

                //Check if any routing has been done
               //do logic here
               $holi = true; //Role::where('id', $id)->first();

                if ($can_be_deleted) {
                    if ($holi) {
                        DB::beginTransaction();
                        //Delete Query  details
                        WorkSchedule::where('id', $id)->delete();

                        DB::commit();

                        $output = ['success' => true,
                                'msg' => "Holiday Deleted Successfully"
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
}
