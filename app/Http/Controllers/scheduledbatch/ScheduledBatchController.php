<?php

namespace App\Http\Controllers\scheduledbatch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\scheduledbatch\ScheduledBatch;
use Yajra\DataTables\Facades\DataTables;
use App\Models\vehicle_units\vehicle_units;



class ScheduledBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {

           $sheduled = ScheduledBatch::with('production_target')->get();

       

       return DataTables::of($sheduled)

        ->addColumn('action', function ($sheduled) {
               return '
                      <a href="' . route('update_batch', [encrypt_data($sheduled->id)]) . '" class="btn btn-xs btn-primary edit_brand_button"><i class="glyphicon glyphicon-edit"></i>Edit</a>
                       &nbsp;
                      <a href="' . route('querycategory.destroy', [encrypt_data($sheduled->id)]) . '" class="btn btn-xs btn-danger delete_brand_button delete-query"><i class="glyphicon glyphicon-trash"></i> Delete</a>
                ';
           })
           ->addColumn('scheduled_date', function ($sheduled) {
            return dateFormat($sheduled->bach_date);
            
        })

           ->addColumn('nseries', function ($sheduled) {
            return $sheduled->production_target->sum('nseries');
        })
        ->addColumn('fseries', function ($sheduled) {
            return $sheduled->production_target->sum('fseries');
        })

           
             ->addColumn('lcv', function ($sheduled) {
               return $sheduled->production_target->sum('lcv');
           })->rawColumns(['action'])

    ->make(true);
      

   }


    return view('scheduledbatch.index');
    }


    public function update_batch($id)
    {
        //

        $data_id=decrypt_data($id);

        if (request()->ajax()) {

            $units = vehicle_units::where('sheduled_id',$data_id)->get();

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
           ->addColumn('offlinedate', function ($units) {
               return dateFormat($units->offline_date);
          })


    ->make(true);
      

   }


       return view('scheduledbatch.edit')->with(compact('id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
