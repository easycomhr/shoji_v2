<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_name',
        'description',
    ];

    public function employeePositions()
    {
        return $this->hasMany(EmployeePosition::class);
    }
}
