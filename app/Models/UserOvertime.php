<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOvertime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'overtime_type_id', 'overtime_date', 'hours', 'modified_hours', 'is_approved'
    ];

    protected $casts = [
        'overtime_date' => 'date',
        'hours' => 'decimal:2',
        'modified_hours' => 'decimal:2',
        'is_approved' => 'boolean',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function overtimeType()
    {
        return $this->belongsTo(OvertimeType::class);
    }

    // === SCOPES ===

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeForPeriod($query, $fromDate, $toDate)
    {
        return $query->whereBetween('overtime_date', [$fromDate, $toDate]);
    }

    public function scopeByType($query, $overtimeTypeIds)
    {
        return $query->whereIn('overtime_type_id', (array) $overtimeTypeIds);
    }

    // === ACCESSORS ===

    /**
     * Số giờ tăng ca thực tế (ưu tiên modified_hours nếu có)
     */
    public function getEffectiveHoursAttribute()
    {
        return $this->modified_hours ?? $this->hours;
    }
}
