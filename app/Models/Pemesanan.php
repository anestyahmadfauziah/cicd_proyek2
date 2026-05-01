<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'tanggal_pemesanan',
        'jumlah_tiket',
        'total_harga',
        'status',
        'id_user',
        'id_destinasi',
        'user_uuid',
        'order_id',
        'tanggal_berangkat',
        'midtrans_status'
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'tanggal_berangkat' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'id_destinasi', 'id_destinasi');
    }

    public function user()
    {
          return $this->belongsTo(User::class, 'user_uuid', 'id');
    }

    public function getStatusFinalAttribute()
{
    $status = $this->midtrans_status ?? $this->status;

    return match ($status) {
        'success', 'settlement', 'capture' => 'Berhasil',
        'pending' => 'Pending',
        'expire', 'cancel', 'deny' => 'Gagal',
        default => ucfirst($status),
    };
}
}