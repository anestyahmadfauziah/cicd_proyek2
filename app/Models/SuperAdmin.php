<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    use HasFactory;

    protected $table = 'super_admin';

    protected $primaryKey = 'username'; // atau hapus kalau UUID default

    public $incrementing = false; // kalau UUID
    protected $keyType = 'string';

    protected $fillable = [
    'username',
    'password',
    'role',
    'status',
    'first_name',
    'last_name',
    'email',
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