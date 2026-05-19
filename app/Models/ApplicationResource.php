<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_name',
        'application_module_id',
    ];

    public function applicationModule()
    {
        return $this->belongsTo(ApplicationModule::class);
    }
}
