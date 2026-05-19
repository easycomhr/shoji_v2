<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_id',
        'schedule_date',
        'department_id',
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class, 'shift_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
