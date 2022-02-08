<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;


      protected $fillable = [
        'name', 'email', 'username','phone_no', 'status', 'password',
    ];

     protected $table = 'appusers';

       protected $hidden = [
        'password', 'created_at', 'updated_at',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
