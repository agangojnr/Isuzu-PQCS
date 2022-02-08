<?php

namespace App\Exports;

use App\Models\attendance\Attendance;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OvertimeExport implements FromCollection,WithHeadings
{

    public function headings():array{
        return[
            '#',
            'Date',
            'Shop',
            'Staff ID',
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $record = Attendance::where('id','<',10)->get(['date','staff_id','shop_id']);
        return $record;
    }
}
