<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_loan_id',
        'amount',
        'payment_date',
        'remaining_balance',
        'or_number'
    ];
}
