<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['amount_payable', 'type', 'or_number', 'amount_discounted', 'is_paid', 'discount_percentage'];

    public function patientVisit() {
        return $this->hasOne(PatientVisit::class);
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
