<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnualLeave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'user_code',
        'year',
        'total_leave_days',
        'used_leave_days',
        'remaining_leave_days',
        'annual_transfer',
        'transfer_used_by_march',
        'transfer_cleared',
        'total_accrued_in_year',
    ];

    /**
     * Mối quan hệ với bảng users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
