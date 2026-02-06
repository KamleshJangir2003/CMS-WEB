<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Show Registration Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle Registration
    public function register(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:employees',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:admin,employee,client', // Add user_type validation
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Auto-approve admin users, others need approval
        $isApproved = $request->user_type == 'admin' ? true : false;

        // Create Employee
        $employee = Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'is_approved' => $isApproved,
        ]);

        // Auto-login for admin, others need to wait for approval
        if ($isApproved) {
            Auth::login($employee);

            return $this->redirectToDashboard($employee->user_type);
        }

        return redirect()->route('login')
            ->with('success', 'Registration successful! '.
                   ($request->user_type == 'admin' ?
                    'Admin account created.' :
                    'Your account is pending admin approval.'));
    }

    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login - UPDATED VERSION
    public function login(Request $request)
    {
        // Validate with user_type
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_type' => 'required|in:admin,employee,client',
        ]);

        // Find user in employees table
        $employee = Employee::where('email', $request->email)
            ->where('user_type', $request->user_type)
            ->first();

        if (! $employee) {
            return back()->withErrors([
                'email' => 'No account found with these credentials for the selected user type.',
            ])->withInput($request->only('email', 'user_type', 'remember'));
        }

        if (! $employee->is_approved) {
            return back()->withErrors([
                'email' => 'Your account is pending admin approval. Please wait for approval.',
            ])->withInput($request->only('email', 'user_type', 'remember'));
        }

        if (! Hash::check($request->password, $employee->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('email', 'user_type', 'remember'));
        }

        Auth::login($employee, $request->has('remember'));
        $request->session()->regenerate();

        return $this->redirectToDashboard($employee->user_type);
    }

    // Helper function to redirect to dashboard based on user type
    private function redirectToDashboard($userType)
    {
        switch ($userType) {
            case 'admin':
                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'Welcome Admin!');

            case 'employee':
                return redirect()->intended('/employee/dashboard')
                    ->with('success', 'Welcome Employee!');

            case 'client':
                return redirect()->intended('/client/dashboard')
                    ->with('success', 'Welcome Client!');

            default:
                return redirect()->intended('/dashboard');
        }
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }

    // ADMIN ONLY: Approve pending users
    public function approveUser($id)
    {
        // Check if current user is admin
        if (! Auth::check() || Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $employee = Employee::findOrFail($id);
        $employee->is_approved = true;
        $employee->save();

        return back()->with('success', 'User approved successfully!');
    }

    // ADMIN ONLY: Get pending approvals
    public function getPendingApprovals()
    {
        if (! Auth::check() || Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $pendingUsers = Employee::where('is_approved', false)->get();

        return view('admin.pending-approvals', compact('pendingUsers'));
    }
}
