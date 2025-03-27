<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PatientLoan extends Model
{
    use HasFactory, Notifiable;

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
        'net_amount_released',
        'notification_date'
    ];

    protected $appends = [
        'amount_formatted',
        'total_interest_amount',
        'cpu_amount',
        'total_deductions',
        'remaining_balance',
        'monthly_payment'
    ];

    public function getAmountFormattedAttribute() {
        return 'P' . number_format($this->amount, 2);
    }

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function payments() {
        return $this->hasMany(LoanPayment::class);
    }

    public function getRemainingBalanceAttribute() {
        return $this->amount - $this->payments->sum('amount');
    }

    public function getMonthlyPaymentAttribute() {
        return number_format($this->amount / $this->duration_months,2);
    }

    // Deductions
    public function getTotalInterestAmountAttribute() {
        return (($this->amount * $this->interest_rate) / 100) * $this->duration_months;
    }

    public function getCpuAmountAttribute() {
        return (($this->amount * $this->capital_build_up) / 100);
    }

    public function getTotalDeductionsAttribute() {
        return $this->service_fee + $this->total_interest_amount + $this->cpu_amount;
    }
}
