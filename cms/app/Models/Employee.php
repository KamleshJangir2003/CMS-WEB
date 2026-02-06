<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'employees';

    protected $fillable = [
        // Basic Info
        'first_name',
        'last_name',
        'email',
        'phone',
        'department',

        // Additional Personal Details
        'father_name',
        'mother_name',
        'dob',
        'contact_number',
        'guardian_number',
        'gender',

        // Address Details
        'address',
        'city',
        'state',
        'pincode',

        // Previous Employment
        'last_company_name',
        'last_salary_in_hand',
        'last_salary_ctc',
        'uan_number',

        // Bank Details
        'bank_name',
        'ifsc_code',
        'bank_account_number',

        // Selfie
        'selfie',

        // System Fields
        'password',
        'user_type',
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'dob' => 'date',
        'last_salary_in_hand' => 'decimal:2',
        'last_salary_ctc' => 'decimal:2',
    ];

    // ==========================
    // ACCESSOR: FULL NAME
    // ==========================
    public function getFullNameAttribute()
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    // ==========================
    // RELATION: PROFILE (OPTIONAL - Ab nahi bhi chahiye)
    // ==========================
    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class, 'employee_id');
    }
}
