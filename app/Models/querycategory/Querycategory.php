<?php

namespace App\Models\querycategory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\querycategory\Traits\QueryCategoryRelationship;

class Querycategory extends Model
{
    use HasFactory,
    QueryCategoryRelationship;
        protected $withCount = ['queries','querieswithoutothers','queryanswers'];
       protected $appends = ['imageUri'];
    public function getImageUriAttribute()
    {
        if (isset($this->attributes['icon'])) {

            return url('upload/') . '/' . $this->attributes['icon'];
        }
    }

    

    protected $fillable = [
        'id',
        'category_name',
        'shop_id',
        'user_id',
        'shop_id',
        'query_code',
        'model_id',
        'category_id',
        'status',
        'query_name',
        'icon',
        'answers',
        'correct_answers',
        'quiz_type',
        'total_correct_answers',
        'total_options',
        'created_at',
        'updated_at',
        'deleted_at',
        'position',
    ];

    
}
