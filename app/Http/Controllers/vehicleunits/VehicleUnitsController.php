<?php

namespace App\Http\Controllers\vehicleunits;

use App\Models\vehicle_units\vehicle_units;
use App\Models\unit_model\Unit_model;
use App\Models\unitroute\Unitroutes;
use App\Models\unitmovement\Unitmovement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\UnitsImport;
use App\Imports\UnitsImportUpdate;
use Yajra\DataTables\Facades\DataTables;
use App\Models\scheduledbatch\ScheduledBatch;
use DB;
use App\Models\productiontarget\ProductionTarget;
use Carbon\Carbon;

class VehicleUnitsController extends Controller
{

     private $upload_temp;

    public function __construct()
    {
        $this->upload_temp = Storage::disk('public');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		

        if (request()->ajax()) {
			
			 $units = vehicle_units::where('status',0)->orWhere('status',1)->get();
			 
			
			// where('status',2)->get();

            // $units = vehicle_units::where('status',0)->get();

        return DataTables::of($units)

          ->addColumn('action', function ($units) {
                return '
                     <a href="' . route('vehicleunits.edit', [$units->id]) . '" class="btn btn-xs btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                        &nbsp;
                       <a href="' . route('vehicleunits.destroy', [$units->id]) . '" class="btn btn-xs btn-danger delete_brand_button delete-unit"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                 ';
            })

            ->addColumn('v_type', function ($units) {

            
                switch ($units->route) {
                    case '1':
                        return 'F-Series';
                        break;
                    case '2':
                        return 'N-Series';
                        break;
                  case '3':
                        return 'N-Series';
                        break;
                  case '4':
                            return 'LCV';
                            break;
                  case '5':
                                return 'LCV';
                                break;
                }
            
       })

         
              ->addColumn('model', function ($units) {
                return $units->model->model_name;
           })
          


     ->make(true);
       

    }


        return view('vehicleunits.index');
    }





    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $template="units";
        $id=0;
        return view('vehicleunits.create')->with(compact('template','id'));
    }

    
    public function updateschedule($id)
    {
        $template="units";
        return view('vehicleunits.changeschedule')->with(compact('template','id'));
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
            'import_file' => 'required|max:30',
            
        ]);



 $extension = File::extension($request->import_file->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {

            } else {

                 $output = ['success' => false,
                            'msg' => "Invalid file format",
                            ];

            }



            // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }
         $move = false;
            $data = array();
            $file = $request->file('import_file');
			
			
			
			
            $filename = date('Ymd_his') . rand(9999, 99999) . $file->getClientOriginalName();
            $data['unit_id'] = $request->unit_id;
            $data['month_date'] = $request->month_date;
            $temp_path = 'temp' . DIRECTORY_SEPARATOR;
			
			

            $move = $this->upload_temp->put($temp_path . $filename, file_get_contents($file->getPathname()));
			
		
			

                if ($move) {
                $response = 1;
                $file_value = $filename;
                
                return view('vehicleunits.step_1', compact('response', 'file_value', 'data'));
            }     



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\vehicle_units  $vehicle_units
     * @return \Illuminate\Http\Response
     */
    public function show(vehicle_units $vehicle_units)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\vehicle_units  $vehicle_units
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          $vehicleunits = vehicle_units::find($id); 
        $models = Unit_model::pluck('model_name', 'id');
        return view('vehicleunits.edit')->with(compact('models','vehicleunits'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\vehicle_units  $vehicle_units
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

      $validator = Validator::make($request->all(), [
            'lot_no' => 'bail|required',
            'vin_no' => 'bail|required',
            'engine_no' => 'bail|required',
            'job_no' => 'bail|required',
            'model_id' => 'bail|required',
            'offline_date' => 'bail|required',
            'route' => 'bail|required',
            
        ]);

             // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }


$exist=$this->check_route($request->input('route'));
     if (!$exist) {

       $output = ['success' => false,
                            'msg' => "Route Selected Does not Exist!!",
                            ];
                            
    }


    $data = $request->only(['lot_no', 'vin_no', 'engine_no', 'job_no','model_id','color','route']);

   $data['offline_date']=date_for_database($request->input('offline_date'));


 if ($validator->passes() && $exist==true) {

         if (request()->ajax()) {
            try {
                      


            $result = vehicle_units::find($id); 
            $result->update($data);
            $result->touch();
             
           $output = ['success' => true,
                            'msg' => "Unit Shedule Updated Successfully"
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
     * @param  \App\Models\vehicle_units  $vehicle_units
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {


               

                $can_be_deleted = true;
                $error_msg = '';


                  $exist=$this->check_if_unit_moved($id);


                if($exist){

                $can_be_deleted = false;

                $error_msg="Vehicle Not Deleted,Unit Already Moved!!";

                }


                     


                //Check if any routing has been done
               //do logic here

                $items = vehicle_units::find($id);
        
            

                if ($can_be_deleted) {
                    if (!empty($items)) {
                        DB::beginTransaction();
                        $items->delete();

                        DB::commit();
                    }

                    $output = ['success' => true,
                                'msg' => "Sheduled Unit Deleted Successfully"
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

     public function samples($file_name)
    {
        $file = Storage::disk('public')->get('sample/' . $file_name);


        return (new Response($file, 200))
            ->header('Content-Type', 'text/csv');
    }


       public function import_process(Request $request)
    {
       
            $temp_path = 'temp' . DIRECTORY_SEPARATOR;
            $path = Storage::disk('public')->exists($temp_path. $request->name);
            if ($path) {
                $path = Storage::disk('public')->path($temp_path. $request->name);


              
                
                 if($request->unit_id=='0'){

                   $date=Carbon::createFromFormat('F Y', $request->month_date)->startOfMonth();;
                    $date=$date->format("Y-m-d");
                    
                    $data['date'] =  $date;


                   // return array('status' => 'Error', 'message' =>  $date);
                  //  exit;
                    $import = new UnitsImport($data);

                 }else{

                    //return array('status' => 'Error', 'message' => decrypt_data($request->unit_id));
                   // exit;
                    $data['unit_id'] = decrypt_data($request->unit_id);
                    $import = new UnitsImportUpdate($data);

                 }
            
               
                      
                       
                try {
                    Excel::import($import, $path);
                } catch (\Exception $e) {
                    Storage::disk('public')->delete($temp_path. $request->name);
                    return array('status' => 'Error', 'message' => 'Import failed check if you are using the right template and you dont have dublicate entry');
                }

                Storage::disk('public')->delete($temp_path. $request->name);
                if (@$import->getRowCount()>-1) {
					
					
                    return json_encode(array('status' => 'Success', 'message' => 'Unit successfully imported' . @$import->getRowCount()));
                } else {
                    
                    return array('status' => 'Error', 'message' => $import);
                }
            }
    
    }

      public function get_units()
    {
        if (request()->ajax()) {
            $search_term = request()->input('term', '');
          

            $result = vehicle_units::where('lot_no', 'LIKE', '%' . $search_term . '%')->orWhere('vin_no', 'LIKE', '%' . $search_term . '%')->get();

            return json_encode($result);
        }
    }



        public function add_unit_row(Request $request)
    {
        if ($request->ajax()) {
            $unit_id = $request->input('unit_id');
          
            
            if (!empty($unit_id)) {
                $index = $request->input('row_count');
                $units = vehicle_units::where('id', $unit_id)->get();;
                
                return view('vehicleunits.partial.show_table_rows')
                        ->with(compact('units', 'index'));
            }
        }
    }


    


    public function check_route($value)
    {
        return  Unitroutes::where('route_number', $value)->exists();
    }

     public function check_if_unit_moved($value)
    {
        return  Unitmovement::where('vehicle_id', $value)->exists();
    }
    
}
