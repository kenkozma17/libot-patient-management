<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PatientVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        // 'invoice_id',
        // 'diagnosis',
        'requesting_physician',
        'visit_date',
        'patient_age',
        'patient_status',
        'patient_type'
    ];

    protected $appends = [
        'visit_date_formatted'
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    // A patient visit has many lab tests through the pivot table
    public function lab_tests(){
        return $this->belongsToMany(
            LabTest::class,
            'patient_visit_lab_tests',
            'patient_visit_id',
            'lab_test_id'
        )->withPivot(['id', 'is_consumed'])->withTimestamps();
    }

    // Access the pivot model directly
    public function patient_visit_lab_tests(){
        return $this->hasMany(PatientVisitLabTest::class);
    }

    public function getVisitDateFormattedAttribute() {
        return Carbon::parse($this->visit_date)
            ->format('M d, Y');
    }

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function results() {
        return $this->hasMany(PatientVisitLabTestResult::class);
    }
}
