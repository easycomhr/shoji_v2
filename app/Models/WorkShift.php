<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkShift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'work_start',
        'work_end',
        'is_day_off',
        'is_night_shift',
        'overtime_type_id',
        'note',
    ];

    protected $casts = [
        'work_start' => 'datetime:H:i',
        'work_end' => 'datetime:H:i',
        'is_night_shift' => 'boolean',
        'is_day_off' => 'boolean',
    ];

    // === RELATIONSHIPS ===

    public function shiftKeys()
    {
        return $this->hasMany(ShiftKey::class);
    }

    public function overtimeTypes()
    {
        return $this->belongsTo(OvertimeType::class);
    }

    // === SCOPES ===

    public function scopeNightShift($query)
    {
        return $query->where('is_night_shift', true);
    }

    public function scopeDayOff($query)
    {
        return $query->where('is_day_off', true);
    }


}
