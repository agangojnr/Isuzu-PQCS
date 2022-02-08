<?php

namespace App\Http\Controllers\employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;

use App\Models\attendance\Attendance;
use App\Models\employee\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\stafftitle\StaffTitle;
use App\Models\shop\Shop;
use App\Models\defaultattendance\DefaultAttendanceHRS;
use App\Models\employeecategory\EmployeeCategory;
use Illuminate\Support\Facades\File;
use App\Imports\EmployeeImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

use App\Exports\StaffExport;
use App\Exports\StaffExportView;
use Excel;

class EmployeeController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
        //Permission::create(['name' => 'hc-list']);
        //Permission::create(['name' => 'hc-create']);
        //Permission::create(['name' => 'hc-edit']);
        //Permission::create(['name' => 'hc-delete']);
        //Permission::create(['name' => 'hc-import']);
        //Permission::create(['name' => 'hc-summary']);
        //Permission::create(['name' => 'set-default']);
    private $upload_temp;

    function __construct()
    {
        $this->upload_temp = Storage::disk('public');


        $this->middleware('permission:hc-list|hc-create|hc-edit|hc-delete', ['only' => ['index','importemployee']]);
        $this->middleware('permission:hc-import', ['only' => ['importemployee','import']]);
        $this->middleware('permission:hc-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:hc-delete', ['only' => ['destroy']]);
        $this->middleware('permission:hc-activate', ['only' => ['activate']]);
         $this->middleware('permission:hc-summary', ['only' => ['staffsummary']]);
         $this->middleware('permission:set-default', ['only' => ['sethours','setdefaulthrs']]);

    }

    public function index()
    {
        $staffs = Employee::where('outsource','=','no')->get();
        return view('employee.index')->with('staffs', $staffs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $titles = StaffTitle::pluck('description', 'id');
        $shops = Shop::Where('overtime','=','1')->pluck('report_name', 'id');
        //$divs = Division::pluck('division_name', 'id');
        //$depts = Department::pluck('department_name', 'id');
        $empcats = EmployeeCategory::pluck('emp_category', 'id');

        $data = array(
            'titles'=> $titles,
            'shops' => $shops,
            'empcats'=>$empcats,
        );
        return view('employee.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $validator = Validator::make($request->all(), [
            'staffno' => 'required|unique:employees,staff_no,',
            'staffname' => 'required',
            'department' => 'required',
            'category' => 'required',
            'shop' => 'required',
            'teamleader' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            Toastr::error('Sorry! Staff No should be unique. Fill all fields');
            return redirect('/employee');
        }

        try{
            DB::beginTransaction();
            $staff = new Employee;
            $existstaff = Employee::where('staff_no', '=', $request->input('staffno'))->first();

            if($existstaff == null){
                $staff->unique_no = Employee::max('unique_no') + 1;
                $staff->staff_no = $request->input('staffno');
                $staff->staff_name = $request->input('staffname');
                $staff->shop_id = $request->input('shop');
                $staff->Department_Description = $request->input('department');
                $staff->Category = $request->input('category');
                $staff->team_leader = $request->input('teamleader');
                $staff->status = $request->input('status');
                $staff -> user_id = auth()->user()->id;

                $staff->save();
                DB::commit();

                Toastr::success('Staff added successfully!','Saved');
                return redirect('/employee');
            }
        }
        catch(\Exception $e){
            DB::Rollback();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            Toastr::error($e->getMessage());
            return redirect('/employee');
        }




        /*}else{
            Toastr::warning('Oops! Code already exist!','Error!');
            return redirect('employee/create');
        }*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shops = Shop::Where('overtime','=','1')->get(['report_name', 'id']);
        $staffs = Employee::find($id);

        $data = array(
            'shops' => $shops,
            'staffs'=>$staffs,
        );

        return view('employee.edit')->with($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'staffno' => 'required',
            'staffname' => 'required',
            'shop' => 'required',
            'teamleader' => 'required',
            'status' => 'required',
        ]);


        if ($validator->fails()) {
            Toastr::error('Sorry! All fields are required.');
            return back();
        }

        try{
            DB::beginTransaction();
                $staff = Employee::find($id);
            $existstaff = Employee::where('staff_no', '=', $request->input('staffno'))->first();

				$uniqueno = Employee::max('unique_no');
				$staff->unique_no = $uniqueno + 1;
            //if($existstaff == null){
                $staff->staff_no = $request->input('staffno');
            //}
                $staff->staff_name = $request->input('staffname');

                $staff->shop_id = $request->input('shop');
                $staff->team_leader = $request->input('teamleader');
                $staff->status = $request->input('status');
				$staff->outsource = 'no';
                $staff->save();
            DB::commit();

            Toastr::success('Staff updated successfully!','Updated');
            return back();
        }
        catch(\Exception $e){
            DB::rollback();
            Toastr::error('An error occured, Employee not updated.','Whoops!');
            return back();
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
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
                        Attendance::where('staff_id', $id)->delete();
                        $emp->delete();

                        DB::commit();

                        $output = ['success' => true,
                                'msg' => "Employee Deleted Successfully"
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

    public function importemployee(){
        $template="employee";
        $data= array(
            'template'=>$template,
        );
        return view('employee.importpage')->with($data);
    }

    public function import_Employees(Request $request){

        $empfile = $request->file('file')->store('temp');
        return Excel::import(new EmployeeImport, $empfile);


        Toastr::success('Record Uploaded successfully','Saved');
        return back();
    }


    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|max:30',

        ]);
        if ($validator->fails()) {
            Toastr::error("It appears you have forgotten to complete something");
            return back();
         }

        $extension = File::extension($request->file->getClientOriginalName());
            if ($extension != "xlsx" && $extension != "xls" && $extension != "csv") {
                Toastr::error('Invalid file format.','Error');
                return back();
            }


            //$move = false;
            //$data = array();
            $file = $request->file('file');
            $filename = date('Ymd_his') . rand(9999, 99999) . $file->getClientOriginalName();
            //$data['unit_id'] = $request->unit_id;
            $temp_path = 'temp' . DIRECTORY_SEPARATOR;

            $move = $this->upload_temp->put($temp_path . $filename, file_get_contents($file->getPathname()));

            //$empfile = $request->file('file')->store('temp');
            $path = Storage::disk('public')->path($temp_path. $filename);
            Excel::import(new EmployeeImport,$path);

        Toastr::success('Record Uploaded successfully','Saved');
        return back();
    }


    public function staffsummary(){
        //$catnames = EmployeeCategory::get(['id','emp_category']);
        $shops = Shop::where('overtime','=','1')->get(['id','report_name']);
        foreach($shops as $sp){
            $hc[$sp->id]  = Employee::where([['shop_id','=',$sp->id],['status','=','Active'],['outsource','=','no']])->count('id');
            //$male = Employee::where([['shop_id','=',$sp->id],['status','=','Active'],['gender','=','Male']])->count('id');
            //$female = $hc[$sp->id]  - $male;
            //$gender[$sp->id] = $male.' M & '.$female.' F';
            $tl[$sp->id] = Employee::where([['shop_id','=',$sp->id],['status','=','Active'],['team_leader','=','yes']])->count('id');
        }
        $data = array(
            'shops'=>$shops,
            'hc'=>$hc,
            'tl'=>$tl,

        );
        return view('employee.staffsummary')->with($data);
    }

    public function sethours(){
        $id = DefaultAttendanceHRS::orderBy('id', 'desc')->take(1)->value('id');
        $direct = DefaultAttendanceHRS::where('id','=',$id)->value('direct');
        $indirect = DefaultAttendanceHRS::where('id','=',$id)->value('indirect');
        $overtime = DefaultAttendanceHRS::where('id','=',$id)->value('overtime');
        $hrslimit = DefaultAttendanceHRS::where('id','=',$id)->value('hrslimit');
        $data = array(
            'direct' => $direct,
            'indirect' => $indirect,
            'overtime'=>$overtime,
            'hrslimit' => $hrslimit,
        );
        return view('employee.sethours')->with($data);
    }

    public function setdefaulthrs(Request $request){
        $validator = Validator::make($request->all(), [
            'direct' => 'required',
            'indirect' => 'required'
        ]);

        $direct = $request->input('direct');
        $indirect = $request->input('indirect');
        $hrslimit = $request->input('hrslimit');
        if(($direct > $hrslimit) || ($indirect > $hrslimit)){
            Toastr::error('Sorry! Limit hours must be greater than direct and indirect hours');
            return back();
        }


        if ($validator->fails()) {
            Toastr::error('Sorry! Fill all fields');
            return back();
        }

        try{
            DB::beginTransaction();

                 DefaultAttendanceHRS::truncate();
                 $data = new DefaultAttendanceHRS;
                $data->direct = $request->input('direct');
                $data->indirect = $request->input('indirect');
                $data->overtime = $request->input('overtime');
                $data->hrslimit = $request->input('hrslimit');
                $data->user_id = auth()->user()->id;
                $data->save();

            DB::commit();
            Toastr::success('Record Saved successfully','Saved');
            return back();

        }

        catch(\Exception $e){
            DB::rollBack();
            Toastr::error('An error occured.','Whoops!');
            return back();
        }
    }

    public function activate($id){ //return "yess";
        if (request()->ajax()) {
            try {
                $can_be_deleted = true;
                $error_msg = '';

                //Check if any routing has been done
               //do logic here
               $status = Employee::where('id', $id)->value('status');
                ($status=='Active') ? $newstatus = 'Inactive' : $newstatus = 'Active';

                if ($can_be_deleted) {
                    if (!empty($newstatus)) {
                        DB::beginTransaction();
                        //Delete Query  details
                        $emp = Employee::Find($id);
                        $emp->status = $newstatus;
                        $emp->save();

                        DB::commit();

                        $output = ['success' => true,
                                'msg' => "User's Status Changed Successfully"
                            ];
                    }else{
                        $output = ['success' => false,
                                'msg' => "Could not be change User's status."
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

    public function empsample($file_name)
    {
        $file = Storage::disk('public')->get('sample/' . $file_name);

        return (new Response($file, 200))
            ->header('Content-Type', 'text/csv');
    }

    //EXPORT STAFF REPOT
    public function exportstafftoexcel(Request $request)
    {
        $staffs = Employee::where('outsource','=','no')->get();
        $data = array(
            'staffs'=>$staffs,
        );
        return Excel::download(new StaffExportView($data), 'stafflist.xlsx');
    }

}


