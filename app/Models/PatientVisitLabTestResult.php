<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientVisitLabTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_visit_id',
        'result_name',
        'result_path'
    ];
}
