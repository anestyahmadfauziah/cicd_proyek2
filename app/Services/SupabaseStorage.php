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
        $ext      = $file->getClientOriginalExtension();
        $fileName = $folder . '/' . Str::uuid() . '.' . $ext;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->key,
            'Content-Type'  => $file->getMimeType(),
            'x-upsert'      => 'true',
        ])->withBody(
            file_get_contents($file->getRealPath()),
            $file->getMimeType()
        )->post("{$this->url}/storage/v1/object/{$this->bucket}/{$fileName}");

        if ($response->failed()) {
            throw new \Exception('Upload gagal: ' . $response->body());
        }

        return "{$this->url}/storage/v1/object/public/{$this->bucket}/{$fileName}";
    }

    public function delete(string $publicUrl): void
    {
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