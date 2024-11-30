<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_received',
        'expiration_date',
        'lot_number',
        'quantity',
        'date_opened',
        'transaction_type',
        'notes',
        'inventory_item_id'
    ];

    public function inventory_item() {
        return $this->belongsTo(InventoryItem::class);
    }
}
