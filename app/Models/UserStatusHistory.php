<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_status_id',
        'start_date',
        'end_date',
        'note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userStatus()
    {
        return $this->belongsTo(UserStatus::class);
    }
}
