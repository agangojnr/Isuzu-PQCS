<?php

namespace App\Models\targets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\shop\Shop;

class Target extends Model
{
    use HasFactory;

    protected $table = "settarget";

    protected $fillable = ['shop_id','set_date','efficiency','absentieesm','tlavailability','user_id'];

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
