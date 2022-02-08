<?php

namespace App\Models\scheduleissue;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule_issue extends Model
{
    use HasFactory;

    protected $table = "schedule_issue";

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
