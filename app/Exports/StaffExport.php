<?php

namespace App\Exports;

use App\Models\employee\Employee;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\FromCollection;

class StaffExport implements FromCollection,WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            '#',
            'unique_no',
            'staff_no',
            'Staff ID',
        ];
    }

    public function collection()
    {
        //
    }
}
