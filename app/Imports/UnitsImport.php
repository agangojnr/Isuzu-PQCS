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

class UnitsImport implements ToCollection, WithBatchInserts, WithValidation, WithStartRow
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



        ++$this->rows;
		
        foreach ($rows as $row) {
		
		
		
			
           if (count($row) == 6) {
			


                $model_code = $row[0];

                     if (!empty($model_code)) {
                        $model = Unit_model::where(function ($query) use ($model_code) {
                                        $query->where('model_name', ''.$model_code.'')
                                              ->orWhere('model_number', ''.$model_code.'');
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
                    'route' => $row[5],
                    'status' =>0
                ]);*/



                $record = vehicle_units::updateOrCreate(
                    ['vin_no' => $row[3]],
                    ['model_id' => $model_id,
                    'lot_no' => $row[1],
                    'job_no' => $row[2],
                    'engine_no' => $row[4],
                    'route' => $row[5],
                    'sheduled_month'=>$this->data['date'],
                    'status' =>0],

                    
            );



             // $record=$unit->save();

              


            }
           else {
               return false;
           }
        }

   



DB::commit();
} catch (\Exception $e) {


   // DB::rollBack(); // roll back transaction if not completely saved
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
            '5' => 'required',

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
