<?php

namespace Tests\Feature;

use Tests\TestCase;

class RfqTest extends TestCase
{
    /** @test */
    public function dapat_mengirim_rfq_dengan_data_valid()
    {
        $payload = [
            "company" => "PT Maju Bersama",
            "items" => [
                ["sku" => "KCR-001", "product_name" => "Safety Helmet", "qty" => 10, "unit_price" => 75000],
                ["sku" => "KCR-002", "product_name" => "Safety Gloves", "qty" => 20, "unit_price" => 25000]
            ]
        ];

        $response = $this->postJson('/api/rfq', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'DRAFT',
                     'company' => 'PT Maju Bersama',
                     'subtotal' => 1250000,
                     'formatted_subtotal' => 'Rp1.250.000'
                 ]);
    }

    /** @test */
    public function gagal_mengirim_rfq_jika_company_kosong_atau_item_tidak_ada()
    {
        $payload = [
            "company" => "",
            "items" => []
        ];

        $response = $this->postJson('/api/rfq', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['company', 'items']);
    }

    /** @test */
    public function gagal_mengirim_rfq_jika_sku_duplikat_atau_qty_nol()
    {
        $payload = [
            "company" => "PT Sumber Rejeki",
            "items" => [
                ["sku" => "KCR-001", "product_name" => "Safety Helmet", "qty" => 0, "unit_price" => 75000],
                ["sku" => "KCR-001", "product_name" => "Duplicate Helmet", "qty" => 5, "unit_price" => 75000]
            ]
        ];

        $response = $this->postJson('/api/rfq', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['items.0.qty', 'items.1.sku']);
    }
}
