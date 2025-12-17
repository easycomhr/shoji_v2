<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date_worked', 'from_datetime', 'to_datetime',
        'work_start', 'work_end', 'actual_hours'
    ];

    protected $casts = [
        'date_worked' => 'date',
        'from_datetime' => 'datetime',
        'to_datetime' => 'datetime',
        'work_start' => 'datetime:H:i',
        'work_end' => 'datetime:H:i',
        'actual_hours' => 'decimal:2',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === SCOPES ===

    public function scopeForPeriod($query, $fromDate, $toDate)
    {
        return $query->whereBetween('date_worked', [$fromDate, $toDate]);
    }

    public function scopeForMonth($query, $month, $year)
    {
        return $query->whereMonth('date_worked', $month)
            ->whereYear('date_worked', $year);
    }
}
