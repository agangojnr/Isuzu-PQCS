<?php

namespace App\Http\Controllers\outsource;

use App\Models\outsource\Outsource;
use App\Models\employee\Employee;
use App\Models\attendance\Attendance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class OutsourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $marked = $request->input('button');
        if($marked == "marked"){
            $validator = Validator::make($request->all(), [
                'staffno' => 'required',
                'staffname' => 'required',
                'overtime' => 'required',
                'authhrs' => 'required',            
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'staffno' => 'required',
                'staffname' => 'required',            
            ]);        
        }

        if ($validator->fails()) {
            Toastr::error('Sorry! All fields are required.');
            return back();
        }
        
        $uniqueno = Employee::max('unique_no');
        
        try{
            DB::beginTransaction();
            $emp = new Employee;
            $emp->unique_no = $uniqueno + 1;
            $emp->staff_no = $request->input('staffno');
            $emp->staff_name = $request->input('staffname');
            $emp->Department_Description = "Supply Chain";
            $emp->Category = "Indirect";
            $emp->shop_id = $request->input('shop_id');
            $emp->status = "Active";
            $emp->outsource = "yes";
            $emp->outsource_date = $request->input('date');
            $emp->save();


            if($marked == "marked"){
                $attend = new Attendance;
                $attend->date = $request->input('date');
                $attend->shop_id = $request->input('shop_id');
                $attend->staff_id = Employee::where('unique_no','=',$uniqueno+1)->value('id');
                $attend->auth_othrs = $request->input('authhrs');
                $attend->indirect_othours = $request->input('overtime');
                $attend->user_id = auth()->user()->id;
                $attend->save();
            }

            DB::commit();
            Toastr::success('Outsourced staff saved successfully','Saved');
            return back();
        }
        catch(\Exception $e){
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            Toastr::error('Sorry! An error occured staff not saved.','Error');
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Outsource  $outsource
     * @return \Illuminate\Http\Response
     */
    public function show(Outsource $outsource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Outsource  $outsource
     * @return \Illuminate\Http\Response
     */
    public function edit(Outsource $outsource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outsource  $outsource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outsource $outsource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outsource  $outsource
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $can_be_deleted = true;
                $error_msg = '';

                //Check if any routing has been done
               //do logic here
               $emp = Employee::where('id', $id)->first();

                if ($can_be_deleted) {
                    if (!empty($emp)) {
                        DB::beginTransaction();
                        //Delete Query  details
                        $emp->delete();

                        DB::commit();

                        $output = ['success' => true,
                                'msg' => "Staff Deleted Successfully"
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
