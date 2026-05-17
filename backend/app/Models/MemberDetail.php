<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MemberDetail extends Model
{
    protected $table = 'member_details';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'height_cm',
        'weight_kg',
        'fitness_goals',
        'experience_level'
    ];
    protected $casts = [
        'height_cm' => 'integer',
        'weight_kg' => 'integer'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getBMI()
    {
        if (!$this->height_cm || !$this->weight_kg) {
            return null;
        }
        $height = $this->height_cm / 100;
        return $this->weight_kg / ($height * $height);
    }
}