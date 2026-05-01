<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SemuaHalamanBisaDiAksesTest extends TestCase
{
    
    public function test_login_bisa_diakses(): void
    {
       $this->get('/login')->assertStatus(200);
    }

    public function test_rekomendasi_bisa_diakses(): void
{
    $this->get('rekomendasi')
        ->assertStatus(404);
}
    
}
