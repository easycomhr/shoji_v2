<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'relationship',
        'age',
        'profession',
        'residence_place',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
