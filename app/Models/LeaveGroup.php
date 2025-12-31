<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_groups';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'note',
        'created_user',
        'updated_user',
    ];
}