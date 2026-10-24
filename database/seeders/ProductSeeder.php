<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            ['sku' => 'KCR-001', 'product_name' => 'Safety Helmet', 'unit_price' => 75000, 'stock' => 100],
            ['sku' => 'KCR-002', 'product_name' => 'Safety Gloves', 'unit_price' => 25000, 'stock' => 250],
            ['sku' => 'KCR-003', 'product_name' => 'Safety Boots', 'unit_price' => 150000, 'stock' => 50],
        ]);
    }
}
