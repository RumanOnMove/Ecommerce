<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_public_product_list_api(): void
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }

    public function test_public_product_show_api(): void
    {
        $response = $this->get('/api/products/product-two');

        $response->assertStatus(200);
    }

    public function test_admin_store_product(){
        $this->post('api/login', [
            'email' => 'ruman@email.com',
            'password' => '12345678'
        ]);
        $this->assertAuthenticated();

        $response = $this->post('/api/admin/products', [
            'name' => 'Product One',
            'slug' => Str::slug('Product One'),
            'price' => 355,
            'quantity' => 10,
            'low_stock' => 2
        ]);

        $response->assertStatus(200);
    }
}
