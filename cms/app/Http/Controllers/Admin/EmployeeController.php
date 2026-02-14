<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BulkMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::where('user_type', '!=', 'admin');
        
        // Combined role and platform filtering
        if ($request->has('role') && $request->role) {
            $query->where('user_type', $request->role);
        }
        
        if ($request->has('platform') && $request->platform) {
            $query->where('platform', $request->platform);
        }
        
        // If both role and platform are provided, filter by both
        if ($request->has('role') && $request->has('platform') && $request->role && $request->platform) {
            $query->where('user_type', $request->role)
                  ->where('platform', $request->platform);
        }
        
        $employees = $query->orderBy('first_name')->get();
        
        return view('auth.admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('auth.admin.employees.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:8',
            'user_type' => 'required|in:employee,client,manager',
            'department' => 'required|string|max:255',
            'platform' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'department' => $request->department,
            'platform' => $request->platform,
            'is_approved' => true,
        ]);

        // Create notification
        \App\Helpers\NotificationHelper::employeeAdded($request->first_name . ' ' . $request->last_name);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully!');
    }

    public function allEmployees()
    {
        $employees = Employee::where('user_type', '!=', 'admin')
                        ->where('is_approved', 1)
                        ->orderBy('first_name')
                        ->get();
        
        return view('auth.admin.employees.all-employees', compact('employees'));
    }

    public function sendBulkMail(Request $request)
    {
        $request->validate([
            'emails' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $emails = explode(',', $request->emails);
        $subject = $request->subject;
        $message = $request->message;

        try {
            // For testing - just return success without actually sending
            // Remove this block when mail is properly configured
            return redirect()->back()->with('success', 'Mail would be sent to ' . count($emails) . ' employees! (Mail sending disabled for testing)');
            
            // Uncomment below when mail is configured
            /*
            foreach ($emails as $email) {
                Mail::to(trim($email))->send(new BulkMail($subject, $message));
            }
            return redirect()->back()->with('success', 'Mail sent successfully to ' . count($emails) . ' employees!');
            */
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send mail: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('auth.admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'user_type' => 'required|in:employee,client,manager,admin',
            'department' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee->update($request->only([
            'first_name', 'last_name', 'email', 'user_type', 'department'
        ]));

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        
        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully!');
    }

    public function getEmployeesData()
    {
        $employees = Employee::where('user_type', '!=', 'admin')
                        ->orderBy('first_name')
                        ->get();
        
        return response()->json(['employees' => $employees]);
    }

    public function showDetails($id)
    {
        $employee = Employee::findOrFail($id);
        return view('auth.admin.employees.employee-details', compact('employee'));
    }

    public function profiles()
    {
        $employees = Employee::where('user_type', '!=', 'admin')
                        ->orderBy('first_name')
                        ->get();
        
        return view('auth.admin.employees.profiles', compact('employees'));
    }

    public function employeeList()
    {
        $employees = Employee::where('user_type', '!=', 'admin')
                        ->orderBy('first_name')
                        ->get();
        
        return view('auth.admin.employee-list', compact('employees'));
    }

    public function employeeShifts()
    {
        $employees = Employee::where('user_type', '!=', 'admin')
                        ->orderBy('first_name')
                        ->get();
        
        return view('auth.admin.employees.employee_shift', compact('employees'));
    }

    public function showProfile($id)
    {
        $employee = Employee::findOrFail($id);
        return view('auth.admin.employees.profile-show', compact('employee'));
    }

    public function updateProfile(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'contact_number' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'guardian_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'bank_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
            'bank_account_number' => 'nullable|string|max:50',
            'in_hand_salary' => 'nullable|numeric|min:0',
            'current_ctc' => 'nullable|numeric|min:0',
            'joining_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee->update($request->only([
            'first_name', 'last_name', 'email', 'contact_number', 'phone', 'department',
            'father_name', 'mother_name', 'dob', 'gender', 'guardian_number',
            'address', 'city', 'state', 'pincode', 'bank_name', 'ifsc_code', 'bank_account_number',
            'in_hand_salary', 'current_ctc', 'joining_date'
        ]));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}