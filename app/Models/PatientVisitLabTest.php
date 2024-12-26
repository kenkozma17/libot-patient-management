<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVisitLabTest extends Model
{
    use HasFactory;

    public $table = 'patient_visit_lab_tests';

    protected $fillable = [
        'patient_visit_id',
        'lab_test_id',
        'discount_percentage'
    ];

    public function inventory_items() {
        return $this->belongsToMany(
            InventoryItem::class,
            'patient_visit_lab_test_inventory_items',
            'visit_lab_test_id',
            'inventory_item_id'
        )->withPivot('quantity')->withTimestamps();
    }

    public function lab_test(){
        return $this->belongsTo(LabTest::class);
    }

    public function patient_visits(){
        return $this->belongsTo(PatientVisit::class);
    }
}
