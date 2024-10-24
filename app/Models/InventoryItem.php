<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function invoiceItems()
    {
        return $this->morphMany(InvoiceItem::class, 'itemable');
    }
}
