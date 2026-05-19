<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeRecorderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_date',
        'employee_card_id',
        'direction',
        'recorded_at',
        'user_id',
    ];

    protected $casts = [
        'record_date' => 'date',
        'recorded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
