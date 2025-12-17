<?php
namespace App\Traits;

/**
 * Trait cho việc lấy thông tin theo thời gian
 */
trait TemporalDataTrait
{
    /**
     * Lấy bản ghi có hiệu lực tại một thời điểm
     */
    public function scopeEffectiveAt($query, $date)
    {
        return $query->where('from_date', '<=', $date)
            ->where(function($q) use ($date) {
                $q->whereNull('to_date')
                    ->orWhere('to_date', '>=', $date);
            })
            ->orderBy('from_date', 'desc');
    }

    /**
     * Lấy bản ghi mới nhất trước một thời điểm
     */
    public function scopeLatestBefore($query, $date, $column = 'applied_date')
    {
        return $query->where($column, '<=', $date)
            ->orderBy($column, 'desc');
    }
}