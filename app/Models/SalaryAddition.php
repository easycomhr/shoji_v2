<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAddition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'salary_period_id',
        'addition_amount',
        'note',
    ];

    protected $casts = [
        'addition_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryPeriod()
    {
        return $this->belongsTo(SalaryPeriod::class);
    }
}
