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

    // A lab test belongs to many patient visits through the pivot table
    public function patient_visits()
    {
        return $this->belongsToMany(
            PatientVisit::class,
            'patient_visit_lab_tests',
            'lab_test_id',
            'patient_visit_id'
        )->withTimestamps();
    }

    // A lab test has many inventory items through the pivot table
    public function inventory_items()
    {
        return $this->belongsToMany(
            InventoryItem::class,
            'patient_visit_lab_test_inventory_items',
            'visit_lab_test_id',
            'inventory_item_id'
        )->withPivot('quantity')->withTimestamps();
    }

    // public function invoiceItems()
    // {
    //     return $this->morphMany(InvoiceItem::class, 'itemable');
    // }
}
