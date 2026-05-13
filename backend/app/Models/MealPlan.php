<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    protected $table = 'meal_plans';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'title',
        'total_calories',
        'protein_grams',
        'carbs_grams',
        'fat_grams',
        'plan_date',
    ];

    protected $casts = [
        'total_calories' => 'decimal:2',
        'protein_grams' => 'decimal:2',
        'carbs_grams' => 'decimal:2',
        'fat_grams' => 'decimal:2',
        'plan_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
