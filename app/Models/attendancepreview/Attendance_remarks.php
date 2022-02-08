<?php

namespace App\Models\attendancepreview;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance_remarks extends Model
{
    use HasFactory;

    protected $table = "attendance_remarks";


    protected $fillable = [
        'id',
        'attendance_remarks',
        'created_at',
        'updated_at',
    ];
        
}
