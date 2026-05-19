<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualLeaveByMonth extends Model
{
    use HasFactory;

    protected $table = 'annual_leave_by_month';

    protected $fillable = [
        'user_id',
        'of_year',
        'of_month',
        'allowance_id',
        'allowance_amount',
    ];

    protected $casts = [
        'allowance_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
