<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['amount_payable', 'type', 'or_number', 'amount_discounted', 'is_paid', 'discount_percentage', 'credits_applied'];

    protected $appends = ['total_amount', 'discount_amount', 'total_amount_due'];

    public function getTotalAmountAttribute() {
        return number_format($this->amount_payable, 2);
    }

    public function getTotalAmountDueAttribute() {
        return number_format($this->amount_payable - $this->amount_discounted, 2);
    }

    public function getDiscountAmountAttribute() {
        return number_format($this->amount_discounted, 2);
    }

    public function patientVisit() {
        return $this->hasOne(PatientVisit::class);
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
