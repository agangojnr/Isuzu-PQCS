<?php

namespace App\Imports;

use App\Models\unit_model\Unit_model;
use App\Models\vehicle_units\vehicle_units;
use App\Models\scheduledbatch\ScheduledBatch;
use App\Models\productiontarget\ProductionTarget;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use DB;

class UnitsImportUpdate implements ToCollection, WithBatchInserts, WithValidation, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $rows = 0;
    private $records;

    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }


    public function collection(Collection $rows)
    {



try {
         DB::beginTransaction();

 $job_id = substr(uniqid('job-'), 0, 10);
 $today=date('Y-m-d');
 $batch = ScheduledBatch::find($this->data['unit_id']);

 $id=$batch->id;
 $job_id=$batch->bratch_no;

 


if($id){

        ++$this->rows;
        foreach ($rows as $row) {
            if (count($row) == 8) {


                 $model_code = $row[0];

                      if (!empty($model_code)) {
                        $model = Unit_model::where(function ($query) use ($model_code) {
                                        $query->where('model_name', $model_code)
                                              ->orWhere('model_number', $model_code);
                                    })->first();
                        if (!empty($model)) {
                            $model_id = $model->id;
                        } 
                    }


               /* $unit = new  vehicle_units([

                    'model_id' => $model_id,
                    'lot_no' => $row[1],
                    'job_no' => $row[2],
                    'vin_no' => $row[3],
                    'engine_no' => $row[4],
                    'color' => $row[5],
                    'offline_date' => date_for_database($row[6]),
                    'route' => $row[7],
                    'sheduled_batch_no' => $job_id,
                   'sheduled_id' => $id,

                ]);*/

                

                $record = vehicle_units::updateOrCreate(
                        ['vin_no' => $row[3]],
                        ['sheduled_batch_no' => $job_id,
                        'sheduled_id' => $id,
                        'model_id' => $model_id,
                        'lot_no' => $row[1],
                        'job_no' => $row[2],
                        'engine_no' => $row[4],
                        'color' => $row[5],
                        'offline_date' => date_for_database($row[6]),
                        'route' => $row[7]],
                        
                );



                


             // $record=$unit->updateOrCreate();

              


            }
            else {
               return false;
           }
        }

        //insert summary

       



    $getdate=vehicle_units::select('offline_date')->distinct()->where('sheduled_id',$id)->get();

$allvehicles=vehicle_units::where('sheduled_id',$id)->get();

foreach($allvehicles as $row){

    $master=array();

    foreach($getdate as $rdata){

        $fseries=$rdata->where('offline_date',$rdata->offline_date)->where('sheduled_id',$id)->where('route',1)->count();

        $nseries=$rdata->where('offline_date',$rdata->offline_date)->where('sheduled_id',$id)->where('route',2)->count()+$rdata->where('offline_date',$rdata->offline_date)->where('sheduled_id',$batch->id)->where('route',3)->count();




         $lcv=$rdata->where('offline_date',$rdata->offline_date)->where('sheduled_id',$id)->where('route',4)->count()+$rdata->where('offline_date',$rdata->offline_date)->where('sheduled_id',$batch->id)->where('route',5)->count();


         $master[] = array('date' => $rdata->offline_date,
                           'nseries' => $nseries, 
                           'fseries' => $fseries,
                           'lcv' => $lcv,
                           'sheduled_batch_id' => $batch->id
                        );


    }


}

ProductionTarget::where('sheduled_batch_id',$id)->delete();

foreach($master as $val){
$record = ProductionTarget::create($val);
}

    }







DB::commit();
} catch (\Exception $e) {
    DB::rollBack(); // roll back transaction if not completely saved
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

   return false;
                    
            
            }


    }



    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required',
            '3' => 'required',
            '4' => 'required',
            '7' => 'required',

        ];
    }

    public function batchSize(): int
    {
        return 200;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function startRow(): int
    {
        return 2;
    }
}
