<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_name',
    ];

    public function applicationResources()
    {
        return $this->hasMany(ApplicationResource::class);
    }
}
