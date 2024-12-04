<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
