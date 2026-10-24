<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RfqItem extends Model
{
    protected $fillable = ['rfq_id', 'sku', 'product_name', 'qty', 'unit_price', 'line_total'];

    public function rfq(): BelongsTo
    {
        return $this->belongsTo(Rfq::class);
    }
}
