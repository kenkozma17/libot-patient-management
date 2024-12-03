<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'category_id',
        'senior_price'
    ];

    public function category() {
        return $this->belongsTo(InventoryItemCategory::class);
    }

    // public function invoiceItems()
    // {
    //     return $this->morphMany(InvoiceItem::class, 'itemable');
    // }
}
