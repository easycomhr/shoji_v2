<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'holiday_date', 'year', 'is_active'];

    protected $casts = [
        'holiday_date' => 'date',
        'is_active' => 'boolean',
    ];

    // === SCOPES ===

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeForPeriod($query, $fromDate, $toDate)
    {
        return $query->whereBetween('holiday_date', [$fromDate, $toDate]);
    }

    // === METHODS ===

    /**
     * Kiểm tra một ngày có phải ngày lễ không
     */
    public static function isHoliday($date)
    {
        return static::active()
            ->where('holiday_date', $date)
            ->exists();
    }

    /**
     * Lấy tất cả ngày lễ trong khoảng thời gian
     */
    public static function getHolidaysInPeriod($fromDate, $toDate)
    {
        return static::active()
            ->forPeriod($fromDate, $toDate)
            ->pluck('holiday_date')
            ->toArray();
    }
}
