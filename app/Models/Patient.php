<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory;

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
        'country'
    ];

    protected $appends = [
        'full_name',
        'date_of_birth_with_age',
        'address'
    ];

    public function getFullNameAttribute() {
        return $this->last_name . ', ' . $this->first_name . ' ' . $this->middle_name;
    }

    public function getDateOfBirthWithAgeAttribute() {
        $birthdate = Carbon::parse($this->birthdate);
        return Carbon::parse($birthdate)
            ->format('M d, Y') . ' (' . $birthdate->age  .' years) ';
    }

    public function getAddressAttribute() {
        return $this->street_address . ', '
            . $this->barangay . ', '
            . $this->municipality . ', '
            . $this->province . ', '
            . $this->postal_code;
        }
}
