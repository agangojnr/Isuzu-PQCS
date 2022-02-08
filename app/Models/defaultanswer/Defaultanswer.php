<?php

namespace App\Models\defaultanswer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Defaultanswer extends Model
{
    use HasFactory;


        protected $table = 'default_answer';


     protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
