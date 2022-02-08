<?php

namespace App\Models\reviewconversation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\attendancestatus\Attendance_status;
use App\Models\User;

class Review_conversation extends Model
{
    use HasFactory;

    protected $table = "review_conversations";

    public function status(){
        return $this->belongsTo(Attendance_status::class, 'statusid');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
