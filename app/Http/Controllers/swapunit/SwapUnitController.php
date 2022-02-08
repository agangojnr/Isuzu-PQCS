<?php

namespace App\Http\Controllers\swapunit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\unit_model\Unit_model;
use App\Models\querycategory\Querycategory;
use App\Models\shop\Shop;
use App\Models\unitmovement\Unitmovement;
use Illuminate\Support\Facades\Validator;
use App\Models\queryanswer\Queryanswer;
use App\Models\swapunit\SwapUnits;
use Yajra\DataTables\Facades\DataTables;

class SwapUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        if (request()->ajax()) {

             $swapunuts = SwapUnits::get();

        return DataTables::of($swapunuts)

           ->addColumn('action', function ($swapunuts) {
                return '
                    <a href="#" title="Reset"  class="btn btn-xs btn-success reset-password"><i class="mdi mdi-tooltip-edit"></i> Reset </a>
   
                 ';
            })

            ->addColumn('unitswaped', function ($swapunuts) {
                return $swapunuts->fromvehicle->vin_no;
            })

            ->addColumn('swappedwith', function ($swapunuts) {
                return $swapunuts->tovehicle->vin_no;
            })

        
            ->addColumn('toshop', function ($swapunuts) {
                return $swapunuts->toshop->shop_name;
            })
            ->addColumn('fromshop', function ($swapunuts) {
                return $swapunuts->fromshop->shop_name;
            })

            ->addColumn('doneby', function ($swapunuts) {
                return $swapunuts->user->name;
            })

            ->addColumn('created_at', function ($swapunuts) {
                return dateTimeFormat($swapunuts->created_at);
            })

            
            



    

     ->make(true);
       

    }


        return view('swapunit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::where('check_point', 1)
                            ->pluck('shop_name', 'id');
   $models = Unit_model::pluck('model_name', 'id');
    $querycategory = Querycategory::pluck('category_name', 'id');

   
        return view('swapunit.create')->with(compact('shops','models','querycategory'));
    }

    public function swapunit(Request $request)
    {


       

        $item_data=array();
       

        $item_data['data']=$request->except(['_token']);

 


        if (request()->ajax()) {

            try {//try catch block



        foreach ($item_data['data']['unit_to_id'] as $key => $value) {

            //spaunits
            $swap_unit=strip_tags($item_data['data']['unit_to_id'][$key]);
            $swap_with_unit=strip_tags($item_data['data']['unit_with_swap_id'][$key]);


           // get unit id
            $swap_unit_data=Unitmovement::find($swap_unit);
            $swap_with_unit_data=Unitmovement::find($swap_with_unit);
            $data=array();

          $data['from_shop_id']   = $swap_unit_data->shop_id;
          $data['to_shop_id']   = $swap_with_unit_data->shop_id;
          $data['swap_unit']   = $swap_unit_data->vehicle_id;
          $data['swap_with_unit']   = $swap_with_unit_data->vehicle_id;
          $data['reason']   = $request->reason;
          $data['done_by']   = auth()->user()->id;
          $data['swap_reference']   = substr(uniqid('job-'), 0, 10);


            $result = SwapUnits::create($data);

              if($result->id){
             //get all shops for swap unit
             if(!empty($swap_unit_data) && !empty($swap_with_unit_data) ){
                 //get unit id
                 $swap_unit_id=$swap_unit_data->vehicle_id;
                 $swap_with_unit_id=$swap_with_unit_data->vehicle_id;

                 //get shops to swap 
                 $swap_shop=Unitmovement::where('vehicle_id',$swap_unit_id)->where('shop_id', '<=',5)
                 ->pluck('shop_id');

                 $swap_with_shop=Unitmovement::where('vehicle_id', $swap_with_unit_id)->where('shop_id', '<=',5)
                 ->pluck('shop_id');

                 
                 //queryanser swap ids first
                 $saveswap=Queryanswer::where('vehicle_id',$swap_unit_id)->whereIn('shop_id', $swap_shop)->update(array('swap_id' => $swap_unit_id));

                 $saveswapwith=Queryanswer::where('vehicle_id',$swap_with_unit_id)->whereIn('shop_id', $swap_with_shop)->update(array('swap_id' => $swap_with_unit_id));

                 //queryanser swap vehicle ids

                 $saveswap=Queryanswer::where('swap_id',$swap_unit_id)->whereIn('shop_id', $swap_shop)->update(array('vehicle_id' => $swap_with_unit_id,'swap_reference' => $result->id));

                 $saveswapwith=Queryanswer::where('swap_id',$swap_with_unit_id)->whereIn('shop_id', $swap_with_shop)->update(array('vehicle_id' => $swap_unit_id,'swap_reference' => $result->id));


                 //unitmovement swap ids first

                 $saveswap=Unitmovement::where('vehicle_id',$swap_unit_id)->whereIn('shop_id', $swap_shop)->update(array('swap_id' => $swap_unit_id));

                 $saveswapwith=Unitmovement::where('vehicle_id',$swap_with_unit_id)->whereIn('shop_id', $swap_with_shop)->update(array('swap_id' => $swap_with_unit_id));

                  //unitmovement swap vehicle ids

                  $saveswap=Unitmovement::where('swap_id',$swap_unit_id)->whereIn('shop_id', $swap_shop)->update(array('vehicle_id' => $swap_with_unit_id,'swap_reference' => $result->id));

                  $saveswapwith= Unitmovement::where('swap_id',$swap_with_unit_id)->whereIn('shop_id', $swap_with_shop)->update(array('vehicle_id' => $swap_unit_id,'swap_reference' => $result->id));



             }//end empty

            }// end result


           


        }//end foreach

        $output = ['success' => true,
        'msg' => "Swap Done  Successfully!!"
    ];

    } catch (\Exception $e) {
        \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
        $output = ['success' => false,
                    'msg' => $e->getMessage(),
                    ];
    }//end catch block

    }//end ajax


        return $output;


       

    }

    public function search_swap_unit(Request $request)
    {
       
     
       $q= $request->search;
        $units = Unitmovement::where('current_shop','!=','0')->where('current_shop','<=','5')->whereHas('vehicle', function ($query) use ($q) {
            $query->where('vin_no', 'LIKE', '%' . $q . '%')->orWhere('lot_no', 'LIKE', '%' . $q . '%');
            return $query;
       })->limit(7)->get();  

       

       $output = array();
       foreach ($units as $row) {
           $lable=$row->vehicle->model->model_name.' : '.$row->vehicle->vin_no.' : '.$row->vehicle->lot_no.' : '.$row->shop->shop_name;

        $output[] = array('value' => $row->id, 'label' =>$lable );


    }


    if (count($output) > 0)

    echo json_encode($output);

    //return view('swapunit.partial.search')->withDetails($output);



       
       
       

        
       
    }



    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
