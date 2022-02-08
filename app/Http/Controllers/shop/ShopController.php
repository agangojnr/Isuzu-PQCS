<?php

namespace App\Http\Controllers\shop;

use App\Models\shop\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         if (request()->ajax()) {

              $shops = Shop::All();

        return DataTables::of($shops)

           ->addColumn('action', function ($shops) {
                return '
                   <a href="' . route('shops.edit', [$shops->id]) . '" class="btn btn-xs btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</a>


                 ';
            })
           ->addColumn('color_code', function ($shops) {
           $color=$shops->color_code ;
            return '<span style="background-color:'.$color.'">'.$color.'<span>';
               })->rawColumns(['color_code', 'action'])

     ->make(true);
    }
        return view('shops.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shops.create');
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
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function sections(Request $request)
    {
        $shops = Shop::where('check_shop','=',1)->get(['id','shop_name','no_of_sections']);
        $data = array(
            'shops'=>$shops,
        );
        return view('shops.sections')->with($data);
    }

    public function savesections(Request $request)
    {
        $shopid = $request->input('shopid');
        $noofsections = $request->input('noofsections');

        try{
            DB::beginTransaction();

            for($i = 0; $i < count($shopid); $i++){
                $sect = Shop::find($shopid[$i]);
                $sect->no_of_sections = $noofsections[$i];
                $sect->save();
            }

            DB::commit();

                Toastr::success('Sections saved successfully','Saved');
                return back();

            }
            catch(\Exception $e){
                DB::rollBack();
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
                Toastr::error('Sorry! An error occured sections not saved.','Error');
                return back();
            }


    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shops = Shop::find($id);

        return view('shops.edit')->with(compact('shops'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'shop_name' => 'bail|required',
            'report_name' => 'bail|required',
            'check_point' => 'bail|required',
            'color_code' => 'bail|required',
        ]);



         // Check validation failure
    if ($validator->fails()) {

       $output = ['success' => false,
                            'msg' => "It appears you have forgotten to complete something",
                            ];

    }
   $data = $request->only(['shop_name', 'report_name', 'check_point', 'color_code']);


    if ($validator->passes()) {

         if (request()->ajax()) {
            try {



            $result = Shop::find($id);
            $result->update($data);
            $result->touch();

           $output = ['success' => true,
                            'msg' => "Shop Updated Successfully"
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
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop)
    {
        //
    }
}
