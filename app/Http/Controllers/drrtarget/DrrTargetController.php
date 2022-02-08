<?php

namespace App\Http\Controllers\drrtarget;
use App\Http\Controllers\Controller;
use App\Models\shop\Shop;
use App\Models\drrtarget\DrrTarget;
use App\Models\drrtargetshop\DrrTargetShop;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
class DrrTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (request()->ajax()) {

             $drrtarget = DrrTarget::where('target_type','Drr')->get();

        return DataTables::of($drrtarget)

           ->addColumn('action', function ($drrtarget) {
                return '
                   
                     <a href="' . route('drrtarget.edit', [$drrtarget->id]) . '" class="btn btn-xs btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                        &nbsp;
                       <a href="' . route('drrtarget.destroy', [$drrtarget->id]) . '" class="btn btn-xs btn-danger delete_brand_button delete-user"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                 ';
            })
           ->addColumn('fromdate', function ($drrtarget) {
                return dateFormat($drrtarget->fromdate);
            })
            ->addColumn('todate', function ($drrtarget) {
                return dateFormat($drrtarget->todate);
            })


    

     ->make(true);
       

    }


        return view('drrtarget.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $shops = Shop::where('offline', 1)
                            ->get();
        return view('drrtarget.create')->with(compact('shops'));
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
            'target_name' => 'bail|required|unique:drr_target,target_name',
            'plant_target' => 'bail|required',
            'fromdate' => 'bail|required',
            'todate' => 'bail|required',
            'cv_target' => 'bail|required',
            'lcv_target' => 'bail|required',
            
            
        ]);


         //check if another active status exist



    $exist=$this->activecheck();



    if ($exist && $request->active=='Active' ) {

       $output = ['success' => false,
                            'msg' => "Another Active Target Exist,Disable it First",
                            ];

        return $output;               
             exit;
                            
    }



         // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                         
    }
   $data = $request->only(['target_name', 'plant_target', 'fromdate', 'cv_target','lcv_target','todate','active']);
  
    $data['fromdate'] = date_for_database($request->fromdate);
    $data['todate'] = date_for_database($request->todate);
    $data['target_type'] = 'Drr';
   

   $item_data['query']  = $request->only(['shop_id', 'target_value']);



    
  
    if ($validator->passes()) {

         if (request()->ajax()) {
            try {
                      
             $data = DrrTarget::create($data);    

             if($data->id){

              foreach ($item_data['query']['shop_id'] as $key => $value) {

                    

                     $queries[] = array(
                      'target_id' => $data->id,
                      'shop_id' => $item_data['query']['shop_id'][$key],
                      'target_value' => $item_data['query']['target_value'][$key],
                      
                  );

                    

                  }

                  DrrTargetShop::insert($queries);
            

             
             }       
              
                $output = ['success' => true,
                            'msg' => "Target Set  Successfully"
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $record = DrrTarget::find($id); 
        $shops = Shop::where('offline', 1)->with(['drrtargetshop'=> function ($query) use( $id) {
    $query->where('target_id', $id);
}])->get();
        return view('drrtarget.edit')->with(compact('shops','record'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'target_name' => 'bail|required',
            'plant_target' => 'bail|required',
            'fromdate' => 'bail|required',
            'todate' => 'bail|required',
            'cv_target' => 'bail|required',
            'lcv_target' => 'bail|required',
            
            
        ]);


         // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];
                            
    }
   $data = $request->only(['target_name', 'plant_target', 'fromdate', 'todate','cv_target','lcv_target','active']);
    $data['fromdate'] = date_for_database($request->fromdate);
    $data['todate'] = date_for_database($request->todate);
    $data['target_type'] = 'Drr';

   $item_data['query']  = $request->only(['shop_id', 'target_value']);



    
  
    if ($validator->passes()) {

         if (request()->ajax()) {
            try {
            $result = DrrTarget::find($id);
            $result->update($data);
            $result->touch();
                      

             if($result->id){
             DrrTargetShop::where('target_id', $id)->delete();
              foreach ($item_data['query']['shop_id'] as $key => $value) {


                     $queries[] = array(
                      'target_id' => $result->id,
                      'shop_id' => $item_data['query']['shop_id'][$key],
                      'target_value' => $item_data['query']['target_value'][$key],
                      
                  );

                    

                  }

                  DrrTargetShop::insert($queries);
            

             
             }       
              
                $output = ['success' => true,
                            'msg' => "Target Set  Successfully"
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
     * @param  int  $id
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

                $items = DrrTarget::find($id);
                           
        
            

                if ($can_be_deleted) {
                    if (!empty($items)) {
                        DB::beginTransaction();
                        //Delete Query  details
                        DrrTargetShop::where('target_id', $id)
                                                ->delete();
                        $items->delete();

                        DB::commit();
                    }

                    $output = ['success' => true,
                                'msg' => "Target Deleted Successfully"
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

    public function activecheck()
    {
        return  DrrTarget::where('active', 'Active')->where('target_type', 'Drr')->exists();
    }
}
