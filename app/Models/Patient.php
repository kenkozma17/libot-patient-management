<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'birthdate',
        'street_address',
        'region',
        'province',
        'municipality',
        'barangay',
        'postal_code',
        'phone',
        'email',
        'country',
        'is_member',
        'credit_reset_at',
        'credits'
    ];

    protected $appends = [
        'full_name',
        'date_of_birth_with_age',
        'address',
        'age',
        'credits_formatted'
    ];

    public function getCreditsFormattedAttribute() {
        return 'P' . number_format($this->credits, 2);
    }

    public function getFullNameAttribute() {
        return $this->last_name . ', ' . $this->first_name . ' ' . $this->middle_name;
    }

    public function getDateOfBirthWithAgeAttribute() {
        $birthdate = Carbon::parse($this->birthdate);
        return Carbon::parse($birthdate)
            ->format('M d, Y') . ' (' . $birthdate->age  .' years) ';
    }

    public function getAgeAttribute() {
        $birthdate = Carbon::parse($this->birthdate);
        return $birthdate->age;
    }

    public function getAddressAttribute() {
        return $this->street_address . ', '
            . $this->barangay . ', '
            . $this->municipality . ', '
            . $this->province . ', '
            . $this->postal_code;
        }
}
