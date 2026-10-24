<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreRfqRequest;
use Illuminate\Http\JsonResponse;

class RfqController extends Controller
{
    public function store(StoreRfqRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $subtotal = 0;
        $items = collect($validated['items'])->map(function ($item) use (&$subtotal) {
            $lineTotal = $item['qty'] * $item['unit_price'];
            $subtotal += $lineTotal;

            return [
                'sku' => $item['sku'],
                'product_name' => $item['product_name'],
                'qty' => $item['qty'],
                'unit_price' => $item['unit_price'],
                'line_total' => $lineTotal,
            ];
        });

        return response()->json([
            'status' => 'DRAFT',
            'company' => $validated['company'],
            'items' => $items,
            'subtotal' => $subtotal,
            'formatted_subtotal' => 'Rp' . number_format($subtotal, 0, ',', '.'),
        ], 200);
    }
}