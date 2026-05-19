<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MigrationLog extends Model
{
    protected $fillable = ['table_key', 'synced_at', 'upserted', 'skipped'];
    protected $casts = ['synced_at' => 'datetime'];
}
