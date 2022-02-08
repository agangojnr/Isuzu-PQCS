<?php

namespace App\Models\routingquery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\routingquery\Traits\RoutingQueryRelationship;

class Routingquery extends Model
{
    use HasFactory,
     RoutingQueryRelationship;
    protected $withCount = ['getanswer'];


     protected $fillable = [
        'query_name',
        'can_sign',
        'additional_field',
        'quiz_type',
        'answers',
        'total_options',
        'correct_answers',
        'total_correct_answer',
        'use_defferent_routing',
        'created_at',
        'updated_at',
        'deleted_at',
        'position',
    ];
}
