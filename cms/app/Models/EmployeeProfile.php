<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    use HasFactory;

    protected $table = 'employee_profiles'; // safety

    protected $fillable = [
        'employee_id',
        'email',
        'full_name',
        'father_name',
        'mother_name',
        'dob',
        'contact_number',
        'guardian_number',
        'gender',
        'address',
        'city',
        'state',
        'pincode',
        'last_company_name',
        'last_salary_in_hand',
        'last_salary_ctc',
        'uan_number',
        'bank_name',
        'ifsc_code',
        'bank_account_number',
        'selfie',
    ];

    // ==========================
    // RELATION: EMPLOYEE
    // ==========================
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
