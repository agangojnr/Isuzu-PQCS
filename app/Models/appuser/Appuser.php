<?php

namespace App\Models\appuser;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens; 
use Illuminate\Notifications\Notifiable;
//use OwenIt\Auditing\Contracts\Auditable;
//use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Models\appuser\Traits\AppUserRelationship;

//use Illuminate\Database\Eloquent\Model;

class Appuser extends Authenticatable 
{
    use HasFactory,Notifiable,HasApiTokens,AppUserRelationship;
 
     protected $fillable = [
        'name','device_token', 'email', 'username','phone_no', 'status', 'shop_id', 'password',
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
