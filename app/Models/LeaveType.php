<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'leave_category_id',
        'code',
        'name',
        'kind',
        'paid_rate',
        'note',
    ];

    public function leave_category(){
        return $this->belongsTo(LeaveCategory::class);
    }

}
