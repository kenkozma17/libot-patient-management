<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id'
    ];

    protected $appends = [
        'current_stock'
    ];

    public function invoiceItems()
    {
        return $this->morphMany(InvoiceItem::class, 'itemable');
    }

    public function getCurrentStockAttribute() {
        $latestTransaction = InventoryTransaction::latest()->first();
        if ($latestTransaction) {
            return $latestTransaction->stock ? $latestTransaction->stock : 0;
        }
        return 0;
    }

    public function category() {
        return $this->belongsTo(InventoryItemCategory::class);
    }
}
