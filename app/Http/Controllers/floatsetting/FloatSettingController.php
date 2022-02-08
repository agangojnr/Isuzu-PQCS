<?php

namespace App\Http\Controllers\floatsetting;

use App\Models\float_settings\FloatSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use DB;

class FloatSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (request()->ajax()) {

             $float = FloatSetting::get();

        return DataTables::of($float)

           ->addColumn('action', function ($float) {
                return '
               
                     <a href="' . route('floatsetting.edit', [encrypt_data($float->id)]) . '" class="btn btn-xs btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                        &nbsp;
                       <a href="' . route('floatsetting.destroy', [encrypt_data($float->id)]) . '" class="btn btn-xs btn-danger delete_brand_button delete-float"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                 ';
            })



    

     ->make(true);
       

    }


        return view('floatsetting.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
        return view('floatsetting.create');
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
            'float_name' => 'bail|required|unique:float_settings,float_type',
            'float_type' => 'bail|required',  
            
        ]);

                   // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }

    $data = $request->only(['float_name','float_type']);

     if ($validator->passes()) {

         if (request()->ajax()) {
            try {
                      
             $data = FloatSetting::create($data);    

           
              
                $output = ['success' => true,
                            'msg' => "Float Setting  Created Successfully"
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }
           
        }

    }


    return $output;


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FloatSetting  $floatSetting
     * @return \Illuminate\Http\Response
     */
    public function show(FloatSetting $floatSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FloatSetting  $floatSetting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $id=decrypt_data($id);

         $floatsetting = FloatSetting::find($id); 
        
        return view('floatsetting.edit')->with(compact('floatsetting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FloatSetting  $floatSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'float_name' => 'bail|required',
            'float_type' => 'bail|required', 
            
        ]);



         // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }
   $data = $request->only(['float_name','float_type']);
   
  
    if ($validator->passes()) {

         if (request()->ajax()) {
            try {
                      


            $result = FloatSetting::find($id); 
            $result->update($data);
            $result->touch();
             
           $output = ['success' => true,
                            'msg' => "Float Settings Updated Successfully"
                        ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }
           
        }

    }


    return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FloatSetting  $floatSetting
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

                $id=decrypt_data($id);
  
                $items = FloatSetting::where('id', $id)
                           ->first();
        
            

                if ($can_be_deleted) {
                    if (!empty($items)) {
                        DB::beginTransaction();
                        $items->delete();

                        DB::commit();
                    }

                    $output = ['success' => true,
                                'msg' => "Float Setting Deleted Successfully"
                            ];
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
