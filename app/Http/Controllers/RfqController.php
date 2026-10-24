<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreRfqRequest;
use App\Models\Rfq; 
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB; 

class RfqController extends Controller
{
    public function store(StoreRfqRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Menggunakan Database Transaction untuk keamanan integritas data
        $rfq = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['qty'] * $item['unit_price'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'sku' => $item['sku'],
                    'product_name' => $item['product_name'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                ];
            }

            // Simpan ke tabel rfqs
            $rfq = Rfq::create([
                'company' => $validated['company'],
                'status' => 'DRAFT',
                'subtotal' => $subtotal,
            ]);

            // Simpan relasi ke tabel rfq_items
            $rfq->items()->createMany($itemsData);

            return $rfq->load('items');
        });

        return response()->json([
            'status' => $rfq->status,
            'company' => $rfq->company,
            'items' => $rfq->items,
            'subtotal' => $rfq->subtotal,
            'formatted_subtotal' => 'Rp' . number_format($rfq->subtotal, 0, ',', '.'),
        ], 201);
    }

    public function products(): JsonResponse
    {
        return response()->json(Product::all(), 200);
    }
}