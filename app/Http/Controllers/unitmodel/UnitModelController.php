<?php

namespace App\Http\Controllers\unitmodel;

use App\Models\unit_model\Unit_model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\vehicletype\VehicleType;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
class UnitModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->ajax()) {

             $models = Unit_model::get();

        return DataTables::of($models)

        ->addColumn('action', function ($models) {
                return '
                 <a href="' . route('vehiclemodels.edit', [$models->id]) . '" class="btn btn-xs btn-success edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                        &nbsp;
                       <a href="' . route('vehiclemodels.destroy', [$models->id]) . '" class="btn btn-xs btn-danger delete_brand_button delete-model"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                 ';
            })
             ->addColumn('category', function ($models) {
                return $models->category->vehicle_name;
            })
//->addColumn('model', function ($categories) {
               // return $categories->model->model_name;
           // })


     ->make(true);
       

    }
        return view('unitmodel.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicletypes = VehicleType::pluck('vehicle_name', 'id');
        return view('unitmodel.create')->with(compact('vehicletypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         if (request()->ajax()) {


            try {
               $validator = Validator::make($request->all(), [
            'model_name' => 'required',
            'model_number' => 'required',
            'vehicle_type_id' => 'required',
            
        ]);

          $input = $request->except(['_token']);
          $input['user_id']=1;


          if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }


if ($validator->passes()) {
              $model = Unit_model::create($input);

             
                
                $output = ['success' => true,
                            'msg' => "Model Created Successfully"
                        ];

}



             
               
                
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }

            return $output;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit_model  $unit_model
     * @return \Illuminate\Http\Response
     */
    public function show(Unit_model $unit_model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit_model  $unit_model
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $models = Unit_model::find($id);
        $vehicletypes = VehicleType::pluck('vehicle_name', 'id');

        return view('unitmodel.edit')->with(compact('vehicletypes','models'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit_model  $unit_model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         if (request()->ajax()) {


            try {
               $validator = Validator::make($request->all(), [
            'model_name' => 'required',
            'model_number' => 'required',
            'vehicle_type_id' => 'required',
            
        ]);

          $input = $request->except(['_token']);
         // $input['user_id']=1;


          if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }


if ($validator->passes()) {


              $result = Unit_model::find($id); 
             $result->update($input);
            $result->touch();

             
                
                $output = ['success' => true,
                            'msg' => "Model Updated Successfully"
                        ];

}



             
               
                
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                    
                $output = ['success' => false,
                            'msg' => $e->getMessage(),
                            ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit_model  $unit_model
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

                $items = Unit_model::where('id', $id)
                           ->first();
        
            

                if ($can_be_deleted) {
                    if (!empty($items)) {
                        DB::beginTransaction();
                        $items->delete();

                        DB::commit();
                    }

                    $output = ['success' => true,
                                'msg' => "Model Deleted Successfully"
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
