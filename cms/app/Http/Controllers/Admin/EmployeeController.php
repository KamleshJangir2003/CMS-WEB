<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // =====================
    // LIST EMPLOYEES (SIGNUP + ADMIN)
    // =====================
    public function index()
    {
        $employees = Employee::with('profile')
            ->orderBy('id', 'desc')
            ->get();

        return view('auth.admin.employees.index', compact('employees'));
    }

    // =====================
    // EMPLOYEE SHIFTS PAGE
    // =====================
    public function employeeShifts()
    {
        // Fetch all employees with their names
        $employees = Employee::select('id', 'first_name', 'last_name')
            ->where('user_type', 'employee')
            ->orderBy('first_name', 'asc')
            ->get();

        return view('auth.admin.employees.employee_shift', compact('employees'));
    }

    // =====================
    // SHOW CREATE FORM
    // =====================
    public function create()
    {
        return view('auth.admin.employees.create');
    }

    // =====================
    // STORE EMPLOYEE + PROFILE (ADMIN ADD) - FIXED
    // =====================
    public function store(Request $request)
    {
        // COMPLETE VALIDATION FOR ALL FORM FIELDS
        $validated = $request->validate([
            // Personal Details
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email',
            'father_name' => 'required|string|max:100',
            'mother_name' => 'required|string|max:100',
            'dob' => 'required|date',
            'contact_number' => 'required|string|max:15',
            'guardian_number' => 'required|string|max:15',
            'gender' => 'required|in:male,female,other',

            // Address Details
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',

            // Previous Employment
            'last_company_name' => 'required|string|max:200',
            'last_salary_in_hand' => 'required|numeric',
            'last_salary_ctc' => 'required|numeric',
            'uan_number' => 'required|string|max:50',

            // Bank Details
            'bank_name' => 'required|string|max:100',
            'ifsc_code' => 'required|string|max:20',
            'bank_account_number' => 'required|string|max:30',

            // Job Details
            'department' => 'required|string|max:100',

            // Selfie
            'selfie' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // =====================
        // SELFIE UPLOAD
        // =====================
        $selfiePath = null;
        if ($request->hasFile('selfie')) {
            $selfiePath = $request->file('selfie')
                ->store('employees/selfies', 'public');
        }

        // =====================
        // CREATE EMPLOYEE (MASTER) WITH ALL DATA
        // =====================
        $employee = Employee::create([
            // Basic Info
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->contact_number,
            'department' => $request->department,

            // Additional Personal Details
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'dob' => $request->dob,
            'contact_number' => $request->contact_number,
            'guardian_number' => $request->guardian_number,
            'gender' => $request->gender,

            // Address Details
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,

            // Previous Employment
            'last_company_name' => $request->last_company_name,
            'last_salary_in_hand' => $request->last_salary_in_hand,
            'last_salary_ctc' => $request->last_salary_ctc,
            'uan_number' => $request->uan_number,

            // Bank Details
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'bank_account_number' => $request->bank_account_number,

            // Selfie
            'selfie' => $selfiePath,

            // System Fields
            'user_type' => 'employee',
            'is_approved' => 1,
            'password' => Hash::make('Employee@123'),
        ]);

        // =====================
        // 🔴 IMPORTANT: PROFILE CREATE NAHI KARNA!
        // EmployeeProfile table mein nahi jayega
        // Comment out this entire section
        // =====================
        /*
        EmployeeProfile::create([
            'employee_id' => $employee->id,
            'email' => $employee->email,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'dob' => $request->dob,
            'contact_number' => $request->contact_number,
            'guardian_number' => $request->guardian_number,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'last_company_name' => $request->last_company_name,
            'last_salary_in_hand' => $request->last_salary_in_hand,
            'last_salary_ctc' => $request->last_salary_ctc,
            'uan_number' => $request->uan_number,
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'bank_account_number' => $request->bank_account_number,
            'selfie' => $selfiePath,
        ]);
        */

        // Success message
        return back()->with('success', 'Employee added successfully! Data saved in database.');
    }

    // =====================
    // EDIT PROFILE
    // =====================
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        return view('auth.admin.employees.edit', compact('employee'));
    }

    // =====================
    // UPDATE EMPLOYEE
    // =====================
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        // Validation rules for update
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email,'.$id,
            'contact_number' => 'required|string|max:15',
            'department' => 'required|string|max:100',
            'selfie' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update data
        $updateData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->contact_number,
            'department' => $request->department,
        ];

        // Handle selfie upload
        if ($request->hasFile('selfie')) {
            // Delete old selfie if exists
            if ($employee->selfie) {
                Storage::disk('public')->delete($employee->selfie);
            }
            $updateData['selfie'] = $request->file('selfie')
                ->store('employees/selfies', 'public');
        }

        $employee->update($updateData);

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee updated successfully');
    }

    // =====================
    // DELETE EMPLOYEE
    // =====================
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Delete selfie if exists
        if ($employee->selfie) {
            Storage::disk('public')->delete($employee->selfie);
        }

        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully');
    }

    public function document()
    {
        return view('auth.admin.employees.em_document');
    }

    // =====================
    // EMPLOYEE PROFILES (ALL)
    // =====================
    public function profiles()
    {
        $employees = Employee::where('user_type', 'employee')
            ->orderBy('first_name', 'asc')
            ->get();

        return view('auth.admin.employees.profiles', compact('employees'));
    }

    // =====================
    // SHOW SINGLE EMPLOYEE PROFILE
    // =====================
    public function showProfile($id)
    {
        $employee = Employee::findOrFail($id);
        return view('auth.admin.employees.profile-show', compact('employee'));
    }

    // =====================
    // UPDATE EMPLOYEE PROFILE
    // =====================
    public function updateProfile(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email,'.$id,
            'father_name' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'dob' => 'nullable|date',
            'contact_number' => 'required|string|max:15',
            'guardian_number' => 'nullable|string|max:15',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'department' => 'required|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'ifsc_code' => 'nullable|string|max:20',
            'bank_account_number' => 'nullable|string|max:30',
        ]);

        $employee->update($validated);

        return redirect()->route('admin.employees.profiles')
            ->with('success', 'Employee profile updated successfully!');
    }
}
