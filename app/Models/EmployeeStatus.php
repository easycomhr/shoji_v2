<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeStatus extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status_id', 'applied_date', 'notes'];

    protected $casts = [
        'applied_date' => 'date',
    ];

    // === RELATIONSHIPS ===

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // === SCOPES ===

    public function scopeForDate($query, $date)
    {
        return $query->where('applied_date', '<=', $date)
            ->orderBy('applied_date', 'desc');
    }

    public function scopeByStatus($query, $statusId)
    {
        return $query->where('status_id', $statusId);
    }
}
