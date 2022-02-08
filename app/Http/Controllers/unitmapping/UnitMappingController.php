<?php

namespace App\Http\Controllers\unitmapping;

use App\Models\unitmapping\Unitmapping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UnitMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         if (request()->ajax()) {

             $mapping = Unitmapping::All();

        return DataTables::of($mapping)

         ->addColumn('routes', function ($mapping) {
                return $mapping->routes->name;
            })
         ->addColumn('component', function ($mapping) {
                return $mapping->routes->routing_part;
            })
          ->addColumn('shop', function ($mapping) {
                return $mapping->shop->shop_name;
            })

          ->addColumn('nextshop', function ($mapping) {

                if ($mapping->next_shop_id==0) {

                    return 'END';
                   
                }else{

                    return $mapping->next_shop->shop_name;


                }
                

            })
        
     ->make(true);
       

    }


        return view('unitmapping.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('unit_routing.create');
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
     * @param  \App\Models\Unit_routing  $unit_routing
     * @return \Illuminate\Http\Response
     */
    public function show(Unit_routing $unit_routing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit_routing  $unit_routing
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit_routing $unit_routing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit_routing  $unit_routing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit_routing $unit_routing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit_routing  $unit_routing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit_routing $unit_routing)
    {
        //
    }
}
