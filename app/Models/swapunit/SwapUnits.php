<?php

namespace App\Models\swapunit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\swapunit\Traits\SwapunitRelationship;

class SwapUnits extends Model
{
    use HasFactory,SwapunitRelationship;

    protected $fillable = [
        'from_shop_id',
        'to_shop_id',
        'done_by',
        'swap_unit',
        'swap_with_unit',
        'reason',
        'swap_reference',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
