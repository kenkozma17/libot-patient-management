<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
