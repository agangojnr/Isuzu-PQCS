<?php

namespace App\Exports;

use App\Models\productiontarget\Production_target;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\FromCollection;

class ActualprodnView implements FromView
{
    public function __construct($data) {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view():View
    {
        
        return view('productionschedule.actualproduction_table',$this->data);
    }
}
