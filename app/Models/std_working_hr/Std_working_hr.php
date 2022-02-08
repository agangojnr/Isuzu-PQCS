<?php

namespace App\Models\std_working_hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\std_working_hr\Std_working_hr;

class Std_working_hr extends Model
{
    use HasFactory;

    protected $table = "std_working_hrs";

    protected $fillable = ['shop_id', 'model_id', 'std_hors', 'user_id'];

    public static function getStdHours(){
        $records = Std_working_hr::All();
        return $records;
    }
}
