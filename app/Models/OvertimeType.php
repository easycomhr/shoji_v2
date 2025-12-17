<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'rate_multiplier', 'non_tax_multiplier', 'is_active'
    ];

    protected $casts = [
        'rate_multiplier' => 'decimal:2',
        'non_tax_multiplier' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // === RELATIONSHIPS ===

    public function userOvertimes()
    {
        return $this->hasMany(UserOvertime::class);
    }

    // === SCOPES ===

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
