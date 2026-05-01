<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_kategori'
    ];

    /**
 * @return HasMany<Destinasi, $this>
 */
public function destinasi(): HasMany
{
    return $this->hasMany(Destinasi::class, 'id_kategori', 'id_kategori');
}
}