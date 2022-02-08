<?php

namespace App\Models\shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\shop\Traits\ShopQueryRelationship;

class Shop extends Model
{
    use HasFactory,
    ShopQueryRelationship;

    protected $table = 'shops';

     protected $fillable = [
        'shop_name', 'shop_no', 'report_name','check_shop', 'check_point', 'group_shop', 'color_code',
    ];

}
