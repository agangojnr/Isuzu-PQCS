<?php

namespace App\Http\Controllers\qrcode;

use App\Models\qrcode;
use App\Models\vehicle_units\vehicle_units;
use App\Models\unit_model\Unit_model;
use App\Models\barcode\Barcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use mPDF;

class QrcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $lot = vehicle_units::distinct('lot_no')->pluck('lot_no', 'lot_no');
        $job = vehicle_units::distinct('job_no')->pluck('job_no', 'job_no');
        $model = Unit_model::pluck('model_name', 'id');
        $data=[];
        return view('qrcode.index')->with(compact('lot','job','model','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('qrcode.create');
    }

    public function filterqrcode(Request $request)
    {

        if(!empty($request->lot_no) || !empty($request->job_no) ||  !empty($request->model_id) ){

            $vehicle = vehicle_units::where('model_id', '!=', '0');
          


           if(!empty($request->lot_no)){

            $vehicle->whereIn('lot_no', $request->lot_no);
            }

            
           if(!empty($request->job_no)){

            $vehicle->whereIn('job_no', $request->job_no);
            }
             if(!empty($request->model_id)){

                $vehicle->whereIn('model_id', $request->model_id);
            }
         
       
        $data=$vehicle->get();
       
    
           
        $lot = vehicle_units::distinct('lot_no')->pluck('lot_no', 'lot_no');
        $job = vehicle_units::distinct('job_no')->pluck('job_no', 'job_no');
        $model = Unit_model::pluck('model_name', 'id');


       // return redirect()->route('qrcodefilterresult', ['lot'=>$lot,'job'=>$job,'model'=>$model,'data'=>$data]);

        //return redirect()->route('qrcode.index')->with(compact('lot','job','model','data'));


        return view('qrcode.index')->with(compact('lot','job','model','data'));

        }else{
             return redirect()->route('qrcode.index')->with('message', 'choose one record!!!');


        }

    }

    public function qrcodefilterresult($lot,$job,$model,$data)
    {


    }

    


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $units = $request->get('units');
           $barcode_setting = $request->get('barcode_setting');
        $barcode_details = Barcode::find($barcode_setting);
 
          $unit_details = [];
            $total_qty = 0;

             
            foreach ($units as $value) {
                  $details = vehicle_units:: where('id',$value['unit_id'])->first();
                $unit_details[] = ['details' => $details, 'qty' => $value['quantity']];
                $total_qty += $value['quantity'];
            }



 return view('qrcode.partial.label_print', compact('unit_details', 'barcode_details'))->render();



            /*$html=view('qrcode.partial.label_print', compact('unit_details', 'barcode_details'))->render();


           try {
                $pdf = new \Mpdf\Mpdf(config('pdf'));
                $pdf->WriteHTML($html);
               // if ($style->pdf == 2) {
                    //return $pdf->Output('products_label_print.pdf', 'D');
               // } else {
                      $headers = array(
                        "Content-type" => "application/pdf",
                        "Pragma" => "no-cache",
                        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                        "Expires" => "0"
                );
                return Response::stream($pdf->Output('products_label_print.pdf', 'I'), 200, $headers);
               // }
            } catch (\Exception $e) {
                dd($e->getMessage());
                //return new RedirectResponse(route('qrcode'), ['flash_error' => $e->getMessage()]);
            }*/



 //dd($unit_details);
     
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\qrcode  $qrcode
     * @return \Illuminate\Http\Response
     */
    public function show(qrcode $qrcode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\qrcode  $qrcode
     * @return \Illuminate\Http\Response
     */
    public function edit(qrcode $qrcode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\qrcode  $qrcode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, qrcode $qrcode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\qrcode  $qrcode
     * @return \Illuminate\Http\Response
     */
    public function destroy(qrcode $qrcode)
    {
        //
    }
}
