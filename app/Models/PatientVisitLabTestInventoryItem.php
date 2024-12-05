<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVisitLabTestInventoryItem extends Model
{
    use HasFactory;

    public $table = 'patient_visit_lab_test_inventory_items';

    protected $fillable = [
        'visit_lab_test_id',
        'inventory_item_id',
        'quantity',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function inventory_item()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function patient_visit_lab_test()
    {
        return $this->belongsTo(PatientVisitLabTest::class, 'patient_visit_lab_test_id');
    }

}
