<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'group_id', 'from_date', 'to_date'];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // === SCOPES ===

    public function scopeCurrent($query)
    {
        return $query->whereNull('to_date')
            ->orWhere('to_date', '>=', now());
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('from_date', '<=', $date)
            ->where(function($q) use ($date) {
                $q->whereNull('to_date')
                    ->orWhere('to_date', '>=', $date);
            });
    }
}
