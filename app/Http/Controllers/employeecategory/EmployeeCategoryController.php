<?php

namespace App\Http\Controllers\employeecategory;

use App\Models\employeecategory\EmployeeCategory;
use App\Models\employee\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class EmployeeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = EmployeeCategory::All();
        return view('employeecategory.index')->with('cats', $cats);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employeecategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code'=>'required',
            'description'=>'required'
        ]);

        $category = new EmployeeCategory;

        $code = EmployeeCategory::where('category_code', '=', $request->input('code'))->first();
            if ($code === null) {
                $category -> category_code = $request->input('code');
                $category -> emp_category = $request->input('description');
                $category -> user_id = auth()->user()->id;
                $category->save();

                Toastr::success('Category added successfully!','Success');
                return redirect('/employeecategory');


            } else {
                Toastr::warning('Oops! Code already exist!','Error!');
                return redirect('/employeecategory/create');
                //Toastr::success('Failed :)','Success');
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeCategory  $employeeCategory
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeCategory $employeeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeCategory  $employeeCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cats = EmployeeCategory::find($id);
       return view('employeecategory.edit')->with('cats', $cats);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeCategory  $employeeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code'=>'required',
            'description'=>'required'
        ]);


        $code = EmployeeCategory::where('category_code', '=', $request->input('code'))->first();

            $cats = EmployeeCategory::find($id);

            if ($code == null) {
                    $cats -> category_code = $request->input('code');
                    $cats -> emp_category = $request->input('description');
                } else {
                    $cats -> emp_category = $request->input('description');
                }
                    $cats->save();
                    Toastr::success('Category Successfully Updated!', 'Updated');
                    return redirect('/employeecategory');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeCategory  $employeeCategory
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
               $existemp = Employee::where('empcategory_id', $id)->first();

                if ($can_be_deleted) {
                    if (empty($existemp)) {
                        DB::beginTransaction();
                        //Delete Query  details
                        EmployeeCategory::where('id', $id)
                                                ->delete();
                        DB::commit();

                        $output = ['success' => true,
                                'msg' => "Category Deleted Successfully"
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
