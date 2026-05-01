<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinasiFoto extends Model
{
    protected $table = 'destinasi_fotos';

    protected $fillable = [
        'id_destinasi',
        'foto'
    ];
}