<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'unit',
        'low_stock_limit',
        'days_before_expiration_limit'
    ];

    protected $appends = [
        'current_stock'
    ];

    public function invoiceItems()
    {
        return $this->morphMany(InvoiceItem::class, 'itemable');
    }

    public function getCurrentStockAttribute() {
        $latestTransaction = InventoryTransaction::where('inventory_item_id', $this->id)
            ->latest()
            ->orderBy('id','desc')
            ->first();
        if ($latestTransaction) {
            return $latestTransaction->stock ? $latestTransaction->stock : 0;
        }
        return 0;
    }

    public function category() {
        return $this->belongsTo(InventoryItemCategory::class);
    }

    // Inventory items belong to many lab tests through the pivot table
    public function lab_tests()
    {
        return $this->belongsToMany(
            LabTest::class,
            'patient_visit_lab_test_inventory_items',
            'inventory_item_id',
            'lab_test_id'
        )->withPivot('quantity')->withTimestamps();
    }
}
