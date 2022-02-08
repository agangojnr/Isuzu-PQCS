<?php

namespace App\Models\overtime;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\shop\Shop;
use App\Models\employee\Employee;

class Overtime extends Model
{
    use HasFactory;

    protected $table = "overtimes";

    protected $fillable = ['othours','emp_id','shop_id','user_id','date'];

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}
