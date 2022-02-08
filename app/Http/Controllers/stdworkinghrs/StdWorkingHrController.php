<?php

namespace App\Http\Controllers\stdworkinghrs;

use App\Models\std_working_hr\Std_working_hr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\shop\Shop;
use App\Models\unit_model\Unit_model;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Std_hrs_import;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;

class StdWorkingHrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Unit_Model::All();
        $shopnames = Shop::orderby('shop_no')->Where('check_shop','=','1')->get('report_name');
        $shops = Shop::Where('check_shop','=','1')->get('id');

         $stdhrs_arr = []; $i=1;
        foreach($models as $model){
            $modelid = $model->id;
            $plantsum = 0;
            foreach($shops as $shop){
                $shopid = $shop->id;
                $stdhrs_arr[$modelid][$shopid] = Std_working_hr::where([['shop_id', '=', $shopid], ['model_id', '=', $modelid]])->value('std_hors');
                $plantsum += $stdhrs_arr[$modelid][$shopid];
            }
            $plant[$model->id] = $plantsum;
        }
        //return $shops;
        $data = array(
            'shopnames'=>$shopnames, 'shops'=>$shops,
            'models'=>$models,
            'stdhrs_arr'=>$stdhrs_arr,
            'plant'=>$plant,
        );
        return view('std_working_hrs.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('std_working_hrs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
           $stdhredited = $request->stdhredited;
            $model = $request->modelid;
            $shopid = $request->shopid;

        try{
            DB::beginTransaction();
            for($n = 0; $n < count($model); $n++){
                $existstdhrsid = Std_working_hr::where([['shop_id', '=', $shopid[$n]], ['model_id', '=', $model[$n]]])->value('id');
                $stdhreditedx = ($stdhredited[$n] > 0) ? $stdhredited[$n] : 0;

                if($existstdhrsid == '' && $stdhredited > 0){
                    $insert = new Std_working_hr;
                    $insert->shop_id = $shopid[$n];
                    $insert->model_id = $model[$n];
                    $insert->std_hors = $stdhreditedx;
                    $insert->user_id = auth()->user()->id;
                    $insert->save();

                }elseif($existstdhrsid != ''){
                    $update = Std_working_hr::find($existstdhrsid);
                    $update->std_hors = $stdhreditedx;
                    $update->save();
                }
             }
             DB::commit();
             Toastr::success('Std Hours saved successfully','Saved');
        } catch(\Exception $e){
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            Toastr::error('Error occured standard hours not saved','Error!');
        }

       return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Std_working_hr  $std_working_hr
     * @return \Illuminate\Http\Response
     */
    public function show(Std_working_hr $std_working_hr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Std_working_hr  $std_working_hr
     * @return \Illuminate\Http\Response
     */
    public function edit(Std_working_hr $std_working_hr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Std_working_hr  $std_working_hr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Std_working_hr $std_working_hr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Std_working_hr  $std_working_hr
     * @return \Illuminate\Http\Response
     */
    public function destroy(Std_working_hr $std_working_hr)
    {
        //
    }

    public function importStdhrs(Request $request){
        Excel::import(new Std_hrs_import, $request->file('file')->store('temp'));
        Toastr::success('Record Uploaded successfully','Saved');
        return back();
    }
}
