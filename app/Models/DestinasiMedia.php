<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DestinasiMedia extends Model
{
    protected $table = 'destinasi_media';

    protected $fillable = [
        'id_destinasi',
        'type',
        'url',
        'thumbnail',
    ];

    // ✅ Relasi ke destinasi — sudah benar
    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'id_destinasi', 'id_destinasi');
    }

    // ❌ HAPUS method media() di sini — ini harusnya ada di model Destinasi, bukan di sini

    // ✅ Tambah: auto-generate thumbnail YouTube jika kosong
    protected $appends = ['embed_url', 'platform', 'video_id', 'auto_thumbnail'];

    public function getVideoIdAttribute()
    {
        if ($this->type !== 'video') return null;

        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\&\?\/]+)/', $this->url, $yt);
        if (!empty($yt[1])) return $yt[1];

        preg_match('/vimeo\.com\/(\d+)/', $this->url, $vm);
        return $vm[1] ?? null;
    }

    public function getPlatformAttribute()
    {
        if ($this->type !== 'video') return null;

        if (str_contains($this->url, 'youtube') || str_contains($this->url, 'youtu.be'))
            return 'youtube';
        if (str_contains($this->url, 'vimeo'))
            return 'vimeo';
        return 'unknown';
    }

    public function getEmbedUrlAttribute()
    {
        if ($this->type !== 'video') return null;

        return match($this->platform) {
            'youtube' => 'https://www.youtube.com/embed/' . $this->video_id,
            'vimeo'   => 'https://player.vimeo.com/video/' . $this->video_id,
            default   => $this->url,
        };
    }

    public function getAutoThumbnailAttribute()
    {
        // Kalau sudah ada thumbnail, pakai itu
        if ($this->thumbnail) return $this->thumbnail;

        // Kalau video YouTube, auto-generate dari video ID
        if ($this->type === 'video' && $this->platform === 'youtube') {
            return 'https://img.youtube.com/vi/' . $this->video_id . '/hqdefault.jpg';
        }

        return null;
    }
}