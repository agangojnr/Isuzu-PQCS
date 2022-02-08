<?php

namespace App\Models\float_settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FloatSetting extends Model 
{
    use HasFactory;

        protected $fillable = [
        'float_type','float_name',
    ];

  

       protected $hidden = [
         'created_at', 'updated_at',
    ];


}
