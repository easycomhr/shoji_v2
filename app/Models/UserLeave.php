<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLeave extends Model
{
    use HasFactory;

    protected $table = 'user_leaves';

    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'user_id',
        'user_code',
        'register_date',
        'leave_date',
        'leave_type_id',
        'leave_session_id',
        'leave_amount',
        'comment',
        'sys_marker',
        'approved',
    ];

    protected $casts = [
        'register_date' => 'date',
        'leave_date' => 'date',
    ];

    /**
     * Get the user that owns the leave record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the leave type associated with the leave record.
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeForPeriod($query, $fromDate, $toDate)
    {
        return $query->whereBetween('leave_date', [$fromDate, $toDate]);
    }

    public function scopeByType($query, $leaveTypeIds)
    {
        return $query->whereIn('leave_type_id', (array) $leaveTypeIds);
    }
}