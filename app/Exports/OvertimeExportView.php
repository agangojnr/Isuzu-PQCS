<?php

namespace App\Exports;

use App\Models\attendance\Attendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OvertimeExportView implements FromView
{
    public function __construct($data) {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view():View
    {
        return view('overtime.otreport_table',$this->data);
    }
}
