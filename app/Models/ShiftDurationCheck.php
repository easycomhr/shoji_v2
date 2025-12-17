<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDurationCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'io_date', 'check_in', 'check_out',
        'early_minutes', 'late_minutes', 'early_modify'
    ];

    protected $casts = [
        'io_date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
        'early_minutes' => 'decimal:2',
        'late_minutes' => 'decimal:2',
        'early_modify' => 'decimal:2',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === SCOPES ===

    public function scopeForPeriod($query, $fromDate, $toDate)
    {
        return $query->whereBetween('io_date', [$fromDate, $toDate]);
    }

    public function scopeLate($query)
    {
        return $query->where('late_minutes', '>', 0);
    }

    public function scopeEarly($query)
    {
        return $query->where('early_minutes', '>', 0);
    }
}
