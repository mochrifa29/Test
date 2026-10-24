<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rfq extends Model
{
    protected $fillable = ['company', 'status', 'subtotal'];

    public function items(): HasMany
    {
        return $this->hasMany(RfqItem::class);
    }
}
