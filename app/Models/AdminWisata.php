<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AdminWisata extends Authenticatable
{
    use HasFactory, HasUuids;

    protected $table = 'admin_wisata';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'password',
        'role',
        'status',
        'first_name',
        'last_name',
        'phone',
        'bio',
        'location',
    ];

    protected $hidden = [
        'password',
    ];
    public function getAuthIdentifierName()
{
    return 'username';
}
}