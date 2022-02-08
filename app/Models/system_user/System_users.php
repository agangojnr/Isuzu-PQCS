<?php

namespace App\Models\system_user;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System_users extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'username','phone_no', 'status', 'shop_id', 'password',
    ];

     protected $table = 'system_users';

       protected $hidden = [
        'password', 'created_at', 'updated_at',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
