<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        \Log::info('=== ADMIN LOGIN ATTEMPT ===', $request->all());
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            \Log::info('Admin login successful');
            return redirect()->intended('/admin/dashboard');
        }

        \Log::error('Admin login failed for email: ' . $request->email);
        return back()->withErrors([
            'email' => 'Invalid admin credentials. This is ADMIN login.',
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}