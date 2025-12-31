<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'short_name',
        'area_code',
        'created_user',
        'updated_user',
    ];


    public function users()
    {
        return $this->hasMany(User::class, 'nationality', 'id');
    }
}
