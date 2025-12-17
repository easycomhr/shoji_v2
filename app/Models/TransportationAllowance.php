<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationAllowance extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'allowance', 'applied_date'];

    protected $casts = [
        'allowance' => 'decimal:2',
        'applied_date' => 'date',
    ];

    // === RELATIONSHIPS ===

    public function users()
    {
        return $this->hasMany(User::class, 'transportation_id');
    }

    // === SCOPES ===

    public function scopeForDate($query, $date)
    {
        return $query->where('applied_date', '<=', $date)
            ->orderBy('applied_date', 'desc');
    }
}
