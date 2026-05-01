<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran'; 
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'metode_bayar',
        'tanggal_bayar',
        'status_pembayaran',
        'total_bayar',
        'id_pemesanan'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}