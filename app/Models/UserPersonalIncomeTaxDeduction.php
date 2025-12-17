<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPersonalIncomeTaxDeduction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'from_date', 'number_of_dependents'];

    protected $casts = [
        'from_date' => 'date',
    ];

    public function scopeForDate($query, $date)
    {
        return $query->where('from_date', '<=', $date)
            ->orderBy('from_date', 'desc');
    }
}
