<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    public $timestamps = false;

    protected $fillable = [
        'main_categories',
        'sub_categories',
        'sub_category_score',
        'sequence',
        'criteria_version_id',
    ];

    protected $casts = [
        'sub_category_score' => 'float',
    ];
    
    public function criteriaVersion(): BelongsTo
    {
        return $this->belongsTo(CriteriaVersion::class);
    }

    public function evaluationLists()
    {
        return $this->hasMany(EvaluationList::class, 'categorie_id');
    }
}
