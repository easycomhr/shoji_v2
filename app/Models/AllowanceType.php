<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllowanceType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'allowance_types';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'note',
        'is_tax',
        'is_social_insurance',
        'status',
        'created_user',
        'updated_user',
    ];
}