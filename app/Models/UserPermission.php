<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_id',
        'can_read',
        'can_approve',
    ];

    protected $casts = [
        'can_read' => 'boolean',
        'can_approve' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicationResource()
    {
        return $this->belongsTo(ApplicationResource::class, 'resource_id');
    }
}
