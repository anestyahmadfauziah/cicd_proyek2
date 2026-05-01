<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DestinasiFoto;

class Destinasi extends Model
{
    use HasFactory;

    protected $table = 'destinasi';
    protected $primaryKey = 'id_destinasi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama',
        'deskripsi',
        'lokasi',
        'alamat_lengkap',
        'weekday',
        'weekend',
        'harga_tiket_weekday',
        'harga_tiket_weekend',
        'id_kategori',
        'foto',
        'created_by_id',
        'created_by_role'
    ];

    public function getRouteKeyName()
    {
        return 'id_destinasi';
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // TAMBAHAN UNTUK MULTI FOTO (SLIDER)
    public function fotos()
    {
        return $this->hasMany(DestinasiFoto::class, 'id_destinasi', 'id_destinasi');
    }
    public function creator()
{
    return $this->belongsTo(User::class, 'created_by_id', 'id');
}
}