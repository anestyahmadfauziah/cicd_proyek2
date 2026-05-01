<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class ActivityLog extends Model
{
    protected $fillable = [
        'user_name',
        'role',
        'activity',
        'status'
    ];
}