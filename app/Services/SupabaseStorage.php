<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorage
{
    protected string $url;
    protected string $key;
    protected string $bucket;

    public function __construct()
    {
        $this->url    = config('services.supabase.url');
        $this->key    = config('services.supabase.key');
        $this->bucket = config('services.supabase.bucket');
    }

    public function upload(UploadedFile $file, string $folder = 'covers'): string
{
    // FIX S-01 & S-02: Whitelist ekstensi yang diizinkan
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower($file->getClientOriginalExtension());

    if (!in_array($ext, $allowedExtensions, true)) {
        throw new \InvalidArgumentException('Ekstensi file tidak diizinkan: ' . $ext);
    }

    // FIX S-03: Whitelist MIME type
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $mime = $file->getMimeType() ?? 'application/octet-stream';

    if (!in_array($mime, $allowedMimes, true)) {
        throw new \InvalidArgumentException('Tipe MIME tidak diizinkan: ' . $mime);
    }

    // Setelah divalidasi, ext sudah aman dipakai
    /** @psalm-taint-escape ssrf */
    $safeExt = $ext;

    $fileName = $folder . '/' . Str::uuid() . '.' . $safeExt;

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->key,
        'Content-Type'  => $mime,
        'x-upsert'      => 'true',
    ])->withBody(
        file_get_contents($file->getRealPath()),
        $mime
    )->post("{$this->url}/storage/v1/object/{$this->bucket}/{$fileName}");

    if ($response->failed()) {
        throw new \Exception('Upload gagal: ' . $response->body());
    }

    return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$fileName}";
}

    public function delete(string $publicUrl): void
{
    // FIX S-04: Validasi $publicUrl harus berasal dari domain sendiri
    if (!str_starts_with($publicUrl, $this->url)) {
        throw new \InvalidArgumentException('URL tidak valid.');
    }

    $filePath = str_replace(
        "{$this->url}/storage/v1/object/public/{$this->bucket}/",
        '',
        $publicUrl
    );

    Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->key,
    ])->delete("{$this->url}/storage/v1/object/{$this->bucket}", [
        'prefixes' => [$filePath]
    ]);
}
}