<?php

use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\ExcelUploadController;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {

    Route::get('/documents', [EmployeeDocumentController::class, 'index'])
        ->name('documents');

    Route::post('/documents/upload', [EmployeeDocumentController::class, 'uploadDocument'])
        ->name('documents.upload');

    Route::post('/documents/bank-details', [EmployeeDocumentController::class, 'saveBankDetails'])
        ->name('bank.details');

    Route::post('/documents/submit', [EmployeeDocumentController::class, 'submitForVerification'])
        ->name('documents.submit');

    Route::get('/documents/download/{id}', [EmployeeDocumentController::class, 'downloadDocument'])
        ->name('documents.download');

    Route::get('/documents/view/{id}', [EmployeeDocumentController::class, 'viewDocument'])
        ->name('documents.view');

    Route::delete('/documents/delete/{id}', [EmployeeDocumentController::class, 'deleteDocument'])
        ->name('documents.delete');
});


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get(
    '/admin/employees/documents',
    [EmployeeDocumentController::class, 'adminDocumentsIndex']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.documents.index');

Route::get(
    '/admin/employees/document/{userId}',
    [EmployeeDocumentController::class, 'adminView']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.document');

Route::post(
    '/admin/employees/document/{userId}/upload',
    [EmployeeDocumentController::class, 'adminUploadDocument']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.document.upload');

Route::post(
    '/admin/employees/document/{userId}/bank-details',
    [EmployeeDocumentController::class, 'adminSaveBankDetails']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.document.bank-details');

Route::post(
    '/admin/employees/document/{userId}/submit',
    [EmployeeDocumentController::class, 'adminSubmitForVerification']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.document.submit');

Route::post(
    '/admin/employees/document/{userId}/generate-offer-letter',
    [EmployeeDocumentController::class, 'generateOfferLetter']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.document.generate-offer-letter');


// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Forgot Password
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    return back()->with('status', 'Password reset link sent!');
})->middleware('guest')->name('password.email');

/*
|--------------------------------------------------------------------------
| Excel Upload Route
|--------------------------------------------------------------------------
*/
Route::post('/upload-excel', [ExcelUploadController::class, 'uploadExcel'])
    ->middleware('auth')
    ->name('upload.excel');

/*
|--------------------------------------------------------------------------
| Manual Lead Save Route
|--------------------------------------------------------------------------
*/
Route::post('/save-manual-lead', [LeadController::class, 'saveManualLead'])
    ->middleware('auth')
    ->name('save.manual.lead');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Redirect
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ($user->user_type) {
            'admin' => redirect()->route('admin.dashboard'),
            'employee' => redirect()->route('employee.dashboard'),
            'client' => redirect()->route('client.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            default => view('dashboard', compact('user')),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['check.user.type:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE SHIFTS - FIXED ✅
            |--------------------------------------------------------------------------
            */
            Route::get('/employee-shifts', [EmployeeController::class, 'employeeShifts'])
                ->name('employee.shifts.index');

            /*
            |--------------------------------------------------------------------------
            | SHIFT MANAGEMENT ROUTES
            |--------------------------------------------------------------------------
            */
            Route::get('/shifts', [App\Http\Controllers\Admin\ShiftController::class, 'index'])->name('shifts.index');
            Route::post('/shifts', [App\Http\Controllers\Admin\ShiftController::class, 'store'])->name('shifts.store');
            Route::get('/shifts/data', [App\Http\Controllers\Admin\ShiftController::class, 'getShifts'])->name('shifts.data');
            Route::put('/shifts/{id}', [App\Http\Controllers\Admin\ShiftController::class, 'update'])->name('shifts.update');
            Route::delete('/shifts/{id}', [App\Http\Controllers\Admin\ShiftController::class, 'destroy'])->name('shifts.destroy');
            Route::get('/shifts/{id}/edit', [App\Http\Controllers\Admin\ShiftController::class, 'edit'])->name('shifts.edit');
            Route::post('/shifts/{id}/status', [App\Http\Controllers\Admin\ShiftController::class, 'updateStatus'])->name('shifts.status');

            // Dashboard
            Route::get('/dashboard', function () {

                $stats = [
                    'totalEmployees' => Employee::where('user_type', 'employee')->count(),
                    'pendingApprovals' => Employee::where('is_approved', false)->count(),
                    'totalAdmins' => Employee::where('user_type', 'admin')->count(),
                    'totalClients' => Employee::where('user_type', 'client')->count(),
                    'totalLeads' => \DB::table('leads')->count(),
                    'totalCallbacks' => \DB::table('callbacks')->count(),
                    'totalInterviews' => \DB::table('interviews')->count(),
                    'rejectedInterviews' => \DB::table('interviews')->where('status', 'LIKE', '%reject%')->orWhere('status', 'LIKE', '%Reject%')->count(),
                ];

                $pendingUsers = Employee::where('is_approved', false)->get();

                return view('auth.admin.dashboard', [
                    'user' => Auth::user(),
                    'stats' => $stats,
                    'pendingUsers' => $pendingUsers,
                ]);
            })->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEES (ADD / STORE / LIST / EDIT / UPDATE / DELETE)
            |--------------------------------------------------------------------------
            */
            Route::get('/employees/add', [EmployeeController::class, 'create'])
                ->name('employee.create');

            Route::post('/employees/store', [EmployeeController::class, 'store'])
                ->name('employee.store');

            Route::get('/employees', [EmployeeController::class, 'index'])
                ->name('employees.index');

            Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])
                ->name('employees.edit');

            Route::put('/employees/{id}', [EmployeeController::class, 'update'])
                ->name('employees.update');

            Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])
                ->name('employees.delete');

            Route::get('/employees/profiles', [EmployeeController::class, 'profiles'])
                ->name('employees.profiles');

            Route::get('/employees/{id}/profile', [EmployeeController::class, 'showProfile'])
                ->name('employees.profile.show');

            Route::put('/employees/{id}/profile', [EmployeeController::class, 'updateProfile'])
                ->name('employees.profile.update');

            /*
            |--------------------------------------------------------------------------
            | APPROVE USER
            |--------------------------------------------------------------------------
            */
            Route::post('/approve/{id}', function ($id) {
                $employee = Employee::findOrFail($id);
                $employee->is_approved = true;
                $employee->save();

                return back()->with('success', 'User approved successfully!');
            })->name('approve');

            /*
            |--------------------------------------------------------------------------
            | USERS & ANALYTICS
            |--------------------------------------------------------------------------
            */
            Route::get('/users', function () {
                $users = Employee::all();

                return view('admin.users', compact('users'));
            })->name('users');

            Route::get('/analytics', function () {
                return view('admin.analytics');
            })->name('analytics');

            /*
            |--------------------------------------------------------------------------
            | LEADS MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
            Route::post('/leads/upload', [LeadController::class, 'uploadExcel'])->name('leads.upload');
            Route::post('/leads/{id}/status', [LeadController::class, 'updateStatus'])->name('leads.status');
            Route::get('/leads/{id}/profile', [LeadController::class, 'showProfile'])->name('leads.cv');
            Route::post('/leads/{id}/resume', [LeadController::class, 'uploadResume'])->name('leads.resume.upload');
            Route::get('/leads/resume/{filename}', [LeadController::class, 'viewResume'])->name('leads.resume.view');
            Route::get('/leads/interested', [LeadController::class, 'interested'])->name('leads.interested');
            Route::get('/leads/rejected', [LeadController::class, 'rejected'])->name('leads.rejected');
            
            // Callback routes
            Route::get('/callbacks', [LeadController::class, 'callbacks'])->name('callbacks.index');
            Route::get('/callbacks/count', [LeadController::class, 'getCallbackCount'])->name('callbacks.count');
            Route::put('/callbacks/{id}', [LeadController::class, 'updateCallback'])->name('callbacks.update');
            Route::delete('/callbacks/{id}', [LeadController::class, 'deleteCallback'])->name('callbacks.delete');

            /*
            |--------------------------------------------------------------------------
            | INTERVIEW MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/interviews', [App\Http\Controllers\Admin\InterviewController::class, 'index'])->name('interviews.index');
            Route::get('/interviews/create', [App\Http\Controllers\Admin\InterviewController::class, 'create'])->name('interviews.create');
            Route::post('/interviews', [App\Http\Controllers\Admin\InterviewController::class, 'store'])->name('interviews.store');
            Route::get('/interviews/{interview}', [App\Http\Controllers\Admin\InterviewController::class, 'show'])->name('interviews.show');
            Route::get('/interviews/{interview}/edit', [App\Http\Controllers\Admin\InterviewController::class, 'edit'])->name('interviews.edit');
            Route::put('/interviews/{interview}', [App\Http\Controllers\Admin\InterviewController::class, 'update'])->name('interviews.update');
            Route::delete('/interviews/{interview}', [App\Http\Controllers\Admin\InterviewController::class, 'destroy'])->name('interviews.destroy');
            Route::post('/interviews/generate-link', [App\Http\Controllers\Admin\InterviewController::class, 'generateMeetingLink'])->name('interviews.generate-link');
            Route::post('/interviews/{interview}/result', [App\Http\Controllers\Admin\InterviewController::class, 'updateResult'])->name('interviews.result');
            Route::post('/interviews/{interview}/offer', [App\Http\Controllers\Admin\InterviewController::class, 'makeOffer'])->name('interviews.offer');
            Route::post('/interviews/{interview}/complete', [App\Http\Controllers\Admin\InterviewController::class, 'completeInterview'])->name('interviews.complete');
        });

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['check.user.type:employee'])
        ->prefix('employee')
        ->name('employee.')
        ->group(function () {

            Route::get('/dashboard', function () {
                $user = Auth::user();

                return view('auth.employee.dashboard', [
                    'user' => $user,
                    'full_name' => $user->first_name.' '.$user->last_name,
                ]);
            })->name('dashboard');

            Route::get('/profile', function () {
                return view('employee.profile', ['user' => Auth::user()]);
            })->name('profile');

            Route::get('/tasks', fn () => view('employee.tasks'))->name('tasks');
            Route::get('/reports', fn () => view('employee.reports'))->name('reports');
        });

    /*
    |--------------------------------------------------------------------------
    | CLIENT ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['check.user.type:client'])
        ->prefix('client')
        ->name('client.')
        ->group(function () {

            Route::get('/dashboard', fn () => view('client.dashboard', ['user' => Auth::user()])
            )->name('dashboard');

            Route::get('/profile', fn () => view('client.profile'))->name('profile');
            Route::get('/services', fn () => view('client.services'))->name('services');
        });

    /*
    |--------------------------------------------------------------------------
    | MANAGER ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['check.user.type:manager'])
        ->prefix('manager')
        ->name('manager.')
        ->group(function () {

            Route::get('/dashboard', [App\Http\Controllers\Manager\ManagerInterviewController::class, 'dashboard'])->name('dashboard');
            Route::get('/interviews', [App\Http\Controllers\Manager\ManagerInterviewController::class, 'index'])->name('interviews.index');
            Route::post('/interviews/{interview}/result', [App\Http\Controllers\Manager\ManagerInterviewController::class, 'updateResult'])->name('interviews.result');
            Route::post('/interviews/{interview}/complete', function($id) {
                $interview = App\Models\Interview::findOrFail($id);
                $interview->update(['status' => 'Completed']);
                return response()->json(['success' => true]);
            })->name('interviews.complete');
        });

    /*
    |--------------------------------------------------------------------------
    | COMMON PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', function () {
        $user = Auth::user();
        $view = $user->user_type.'.profile';

        return view()->exists($view)
            ? view($view, compact('user'))
            : view('profile', compact('user'));
    })->name('profile');

    Route::post('/profile/update', function (Request $request) {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:employees,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string',
        ]);

        $user->update($request->only([
            'first_name', 'last_name', 'email', 'phone', 'department',
        ]));

        return back()->with('success', 'Profile updated successfully!');
    })->name('profile.update');

    Route::post('/profile/change-password', function (Request $request) {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    })->name('profile.change-password');
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
