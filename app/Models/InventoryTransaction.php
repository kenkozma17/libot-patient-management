<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_received',
        'is_archived',
        'expiration_date',
        'lot_number',
        'quantity',
        'date_opened',
        'transaction_type',
        'notes',
        'inventory_item_id',
        'patient_visit_id',
    ];

    protected $appends = [
        'created_at_formatted'
    ];

    public function getCreatedAtFormattedAttribute() {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }

    public function inventory_item() {
        return $this->belongsTo(InventoryItem::class);
    }

    public function patient_visit() {
        return $this->belongsTo(PatientVisit::class);
    }
 }
