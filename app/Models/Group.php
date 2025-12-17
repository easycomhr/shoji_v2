<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'working_days_per_month', 'working_hours_per_day', 'is_active'
    ];

    protected $casts = [
        'working_hours_per_day' => 'decimal:1',
        'is_active' => 'boolean',
    ];

    // === RELATIONSHIPS ===

    public function groupHistories()
    {
        return $this->hasMany(GroupHistory::class);
    }

    public function currentUsers()
    {
        return $this->hasManyThrough(
            User::class,
            GroupHistory::class,
            'group_id',
            'id',
            'id',
            'user_id'
        )->whereNull('group_histories.to_date')
            ->orWhere('group_histories.to_date', '>=', now());
    }

    // === SCOPES ===

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
