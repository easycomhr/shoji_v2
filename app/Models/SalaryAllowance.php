<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAllowance extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'month', 'year', 'allowance'];

    protected $casts = [
        'allowance' => 'decimal:2',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === SCOPES ===

    public function scopeForPeriod($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }
}
