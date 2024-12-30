<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'total_price',
        'discount_percentage'
    ];

    protected $appends = [
        'discount_amount'
    ];

    public function itemable()
    {
        return $this->morphTo();
    }

    public function getDiscountAmountAttribute() {
        return number_format(($this->unit_price * $this->discount_percentage) / 100, 2);
    }
}
