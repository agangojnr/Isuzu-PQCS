<?php

namespace App\Exports;

use App\Models\employee\Employee;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StaffExportView implements FromView
{
    public function __construct($data) {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view():View
    {
        return view('employee.index_table',$this->data);
    }
}
