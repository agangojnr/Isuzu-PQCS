<?php

namespace App\Imports;

use App\Models\employee\Employee;
use App\Models\shop\Shop;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class EmployeeImport implements ToCollection, WithBatchInserts, WithValidation, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


   /* public function model(array $row)
    {

        $employee =new  Employee([
            'staff_name' => $row[1],
            'staff_no' => $row[2],
            'Department_Description' => $row[3],
            'Category' => $row[4],
            'shop_id' => 1,
            'team_leader' =>  'yes',
            'user_id'=> auth()->user()->id,
        ]);

    }
    public function startRow(): int
    {
        return 2;
    }*/


    private $rows = 0;
    private $records;

    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }


    public function collection(Collection $rows)
    {
        ++$this->rows;
        foreach ($rows as $row) {
            if (count($row) == 8) {

                        $uniqueno = Employee::where('unique_no','=', $row[0])->value('id');
                            if(!empty($uniqueno)){
                                $shopname = $row[5];
                                $shopid = Shop::where([['report_name','=', $shopname],['overtime','=',1]])->value('id');
                                if(!empty($shopid)){
                                    $emp = Employee::find($uniqueno);
                                    $emp->staff_no = $row[2];
                                    $emp->staff_name = $row[1];
                                    $emp->Department_Description = $row[3];
                                    $emp->Category = $row[4];
                                    $emp->shop_id = $shopid;
                                    $emp->team_leader = (strtoupper($row[6]) =='TEAM LEADER') ? 'yes' : 'no';
                                    $emp->status = $row[7];

                                    $emp->save();
                                }
                            }else{

                                $shopname = $row[5];
                                $shopid = Shop::where([['report_name','=', $shopname],['overtime','=',1]])->value('id');
                                if(!empty($shopid)){
                                    $employee = new  Employee([
                                        'unique_no' => $row[0],
                                        'staff_name' => $row[1],
                                        'staff_no' => $row[2],
                                        'Department_Description' => $row[3],
                                        'Category' => $row[4],
                                        'shop_id' => $shopid,
                                        'team_leader' =>  (strtoupper($row[6]) =='TEAM LEADER') ? 'yes' : 'no',
                                        'status' => $row[7],
										'outsource'=>'no',
                                        'user_id'=> auth()->user()->id,
                                    ]);

                                    $employee->save();
                                }
                            }

            }
            else {
               return false;
           }
        }


    }



    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required',
            '2' => 'required',
            '5' => 'required',
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
        return 3;
    }
}
