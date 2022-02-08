<?php

namespace App\Models\attendance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\employee\Employee;
use App\Models\shop\Shop;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = ['id','date','shop_id','staff_id','direct_hrs','indirect_hrs','loaned_hrs','shop_loaned_to','auth_othrs','othours','otloaned_hrs','otshop_loaned_to'];

    public function employee(){
        return $this->belongsTo(Employee::class, 'staff_id');
    }

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }


}
