<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OvertimeType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'overtime_types';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'from_time',
        'to_time',
        'paid_rate',
        'status',
        'note',
        'created_user',
        'updated_user',
    ];

    protected $casts = [
        'paid_rate' => 'decimal:2',
    ];
}