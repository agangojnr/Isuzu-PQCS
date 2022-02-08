<?php

namespace App\Models\employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\stafftitle\StaffTitle;
use App\Models\department\Department;
use App\Models\division\Division;
use App\Models\shop\Shop;
use App\Models\attendance\Attendance;
use App\Models\employeecategory\EmployeeCategory;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = ['unique_no','staff_no','staff_name','Department_Description','Category','Role','shop_id','team_leader','user_id'];

    public function stafftitle(){
        return $this->belongsTo(StaffTitle::class, 'title_id');
    }

    public function division(){
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function department(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function empcategory(){
        return $this->belongsTo(EmployeeCategory::class, 'empcategory_id');
    }





    //protected $fillable = ['staff_no', 'staff_name', 'title_id', 'division_id','department_id','shop_id','empcategory_id','status','team_leader','user_id'];



}
