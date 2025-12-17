<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftKey extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'user_id', 'shift_id', 'month', 'year',
        'd01', 'd02', 'd03', 'd04', 'd05', 'd06', 'd07', 'd08', 'd09', 'd10',
        'd11', 'd12', 'd13', 'd14', 'd15', 'd16', 'd17', 'd18', 'd19', 'd20',
        'd21', 'd22', 'd23', 'd24', 'd25', 'd26', 'd27', 'd28', 'd29', 'd30', 'd31'
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === METHODS ===

    /**
     * Lấy ca làm việc của một ngày cụ thể
     */
    public function getShiftForDay($day)
    {
        $dayColumn = 'd' . str_pad($day, 2, '0', STR_PAD_LEFT);
        $shiftId = $this->$dayColumn;

        return $shiftId ? WorkShift::find($shiftId) : null;
    }

    /**
     * Set ca làm việc cho một ngày
     */
    public function setShiftForDay($day, $shiftId)
    {
        $dayColumn = 'd' . str_pad($day, 2, '0', STR_PAD_LEFT);
        $this->$dayColumn = $shiftId;
    }

    /**
     * Lấy tất cả ca làm việc trong tháng
     */
    public function getAllShifts()
    {
        $shifts = [];
        for ($day = 1; $day <= 31; $day++) {
            $shifts[$day] = $this->getShiftForDay($day);
        }
        return $shifts;
    }

    /**
     * Đếm số ngày làm việc (không phải ca nghỉ)
     */
    public function getWorkingDaysCount()
    {
        $count = 0;
        for ($day = 1; $day <= 31; $day++) {
            $shift = $this->getShiftForDay($day);
            if ($shift && !$shift->is_day_off) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Đếm số ngày ca đêm
     */
    public function getNightShiftDaysCount()
    {
        $count = 0;
        for ($day = 1; $day <= 31; $day++) {
            $shift = $this->getShiftForDay($day);
            if ($shift && $shift->is_night_shift) {
                $count++;
            }
        }
        return $count;
    }
}
