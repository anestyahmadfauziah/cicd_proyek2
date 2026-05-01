<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $table = 'rekomendasi';

    protected $fillable = [
        'destinasi_id',
        'urutan',
        'is_active'
    ];

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'destinasi_id', 'id_destinasi');
    }
}