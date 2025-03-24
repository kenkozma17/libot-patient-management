<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'interest_rate',
        'purpose',
        'duration_months',
        'start_date',
        'end_date',
        'date_released',
        'check_no',
        'service_fee',
        'capital_build_up',
        'status',
        'patient_id',
        'net_amount_released'
    ];

    protected $appends = [
        'amount_formatted'
    ];

    public function getAmountFormattedAttribute() {
        return 'P' . number_format($this->amount);
    }
}
