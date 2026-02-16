<?php

use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeDocumentController;
use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\SearchController;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalaryCalculatorController;
use App\Models\JobOpening;
use App\Http\Controllers\Admin\JobOpeningController;

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
    '/admin/employees/hired',
    [EmployeeDocumentController::class, 'hiredEmployeesIndex']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.hired.index');

Route::get(
    '/admin/employees/not-selected',
    [EmployeeDocumentController::class, 'notSelectedEmployeesIndex']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.not-selected.index');

Route::post(
    '/admin/employees/hired/{userId}/update',
    [EmployeeDocumentController::class, 'updateHiredEmployee']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.hired.update');

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
    '/admin/employees/document/{userId}/send-offer-letter',
    [EmployeeDocumentController::class, 'sendOfferLetterEmail']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.document.send-offer-letter');

Route::post(
    '/admin/employees/document/{userId}/generate-offer-letter',
    [EmployeeDocumentController::class, 'generateOfferLetter']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.document.generate-offer-letter');

Route::patch(
    '/admin/employees/{id}/hired-status',
    [EmployeeDocumentController::class, 'updateHiredStatus']
)->middleware(['auth', 'check.user.type:admin'])
 ->name('admin.employees.update-hired-status');

// Test route for offer letter
Route::get('/test-offer-letter/{userId}', function($userId) {
    $employee = \App\Models\Employee::findOrFail($userId);
    $bankDetail = \App\Models\EmployeeBankDetail::where('user_id', $userId)->first();
    return view('auth.admin.employees.offer-letter', compact('employee', 'bankDetail'));
})->middleware(['auth', 'check.user.type:admin'])->name('test.offer.letter');


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

                // Gender statistics for hired employees
                $hiredEmployees = Employee::where('user_type', 'employee')->where('is_approved', true);
                $totalHired = $hiredEmployees->count();
                
                // Debug: Check what gender values exist
                $allGenders = Employee::where('user_type', 'employee')
                    ->where('is_approved', true)
                    ->pluck('gender')
                    ->unique()
                    ->filter()
                    ->values();
                
                $maleCount = Employee::where('user_type', 'employee')
                    ->where('is_approved', true)
                    ->where('gender', 'male')
                    ->count();
                    
                $femaleCount = Employee::where('user_type', 'employee')
                    ->where('is_approved', true)
                    ->where('gender', 'female')
                    ->count();
                
                $malePercentage = $totalHired > 0 ? round(($maleCount / $totalHired) * 100) : 0;
                $femalePercentage = $totalHired > 0 ? round(($femaleCount / $totalHired) * 100) : 0;
                
                // Debug info
                \Log::info('Gender Debug', [
                    'total' => $totalHired,
                    'male' => $maleCount,
                    'female' => $femaleCount,
                    'all_genders' => $allGenders->toArray()
                ]);

                $stats = [
                    'totalEmployees' => Employee::where('user_type', 'employee')->count(),
                    'pendingApprovals' => Employee::where('is_approved', false)->count(),
                    'totalAdmins' => Employee::where('user_type', 'admin')->count(),
                    'totalClients' => Employee::where('user_type', 'client')->count(),
                    'totalLeads' => \DB::table('leads')->count(),
                    'totalCallbacks' => \DB::table('callbacks')->where('status', 'call_backs')->count(),
                    'totalInterviews' => \DB::table('interviews')->count(),
                    'rejectedInterviews' => \DB::table('interviews')->where('result', 'Rejected')->count(),
                    'newTickets' => \App\Models\Ticket::where('viewed_at', null)->count(),
                    'totalTickets' => \App\Models\Ticket::count(),
                    'interested' => \DB::table('leads')->where('condition_status', 'Intrested')->count(),
                    'scheduledInterviews' => \DB::table('interviews')->where('status', 'Scheduled')->count(),
                    'employeeHired' => $totalHired,
                    'selectedEmployee' => \DB::table('interviews')->where('result', 'Selected')->count(),
                    // Gender statistics
                    'totalHiredEmployees' => $totalHired,
                    'maleEmployees' => $maleCount,
                    'femaleEmployees' => $femaleCount,
                    'malePercentage' => $malePercentage,
                    'femalePercentage' => $femalePercentage,
                ];
                
                // Get active job openings for popup
                $activeJobOpenings = \App\Models\JobOpening::where('status', 'active')->get();

                $pendingUsers = Employee::where('is_approved', false)->get();
                
                // Birthday employees check
                $todayBirthdays = Employee::whereRaw('DATE_FORMAT(dob, "%m-%d") = ?', [date('m-d')])
                    ->where('dob', '!=', null)
                    ->get();
                    
                // Today's callbacks check
                $todayCallbacks = \DB::table('callbacks')
                    ->whereDate('callback_date', date('Y-m-d'))
                    ->where('status', 'call_backs')
                    ->get();
                    
                // Get all approved employees for the table
                $allEmployees = Employee::where('user_type', 'employee')
                    ->where('is_approved', true)
                    ->select('id', 'first_name', 'last_name', 'email', 'phone', 'department')
                    ->orderBy('first_name')
                    ->get();

                return view('auth.admin.dashboard', [
                    'user' => Auth::user(),
                    'stats' => $stats,
                    'pendingUsers' => $pendingUsers,
                    'todayBirthdays' => $todayBirthdays,
                    'activeJobOpenings' => $activeJobOpenings,
                    'todayCallbacks' => $todayCallbacks,
                    'allEmployees' => $allEmployees,
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

            Route::get('/employees/{id}/details', [EmployeeController::class, 'showDetails'])
                ->name('employees.details');

            Route::get('/employees/data', [EmployeeController::class, 'getEmployeesData'])
                ->name('employees.data');

            Route::get('/employees/list', [EmployeeController::class, 'employeeList'])
                ->name('employees.list');

            Route::get('/employees/all', [EmployeeController::class, 'allEmployees'])
                ->name('employees.all');

            Route::post('/send-bulk-mail', [EmployeeController::class, 'sendBulkMail'])
                ->name('send.bulk.mail');

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
            Route::get('/leads/not-interested', [LeadController::class, 'notInterested'])->name('leads.not-interested');
            Route::get('/leads/wrong-number', [LeadController::class, 'wrongNumber'])->name('leads.wrong-number');
            
            /*
            |--------------------------------------------------------------------------
            | INTERESTED CANDIDATES MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/interested-candidates', [App\Http\Controllers\Admin\InterestedCandidateController::class, 'index'])->name('interested.candidates.index');
            Route::post('/interested-candidates/{id}/status', [App\Http\Controllers\Admin\InterestedCandidateController::class, 'updateStatus'])->name('interested.candidates.status');
            Route::post('/interested-candidates/{id}/notes', [App\Http\Controllers\Admin\InterestedCandidateController::class, 'addNotes'])->name('interested.candidates.notes');
            
            // Callback routes
            Route::get('/callbacks', [LeadController::class, 'callbacks'])->name('callbacks.index');
            Route::get('/callbacks/count', [LeadController::class, 'getCallbackCount'])->name('callbacks.count');
            Route::put('/callbacks/{id}', [LeadController::class, 'updateCallback'])->name('callbacks.update');
            Route::delete('/callbacks/{id}', [LeadController::class, 'deleteCallback'])->name('callbacks.delete');
            Route::post('/callbacks/{id}/update-status', [LeadController::class, 'updateCallbackStatus'])->name('callbacks.update-status');

            /*
            |--------------------------------------------------------------------------
            | INTERVIEW MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/interviews', [App\Http\Controllers\Admin\InterviewController::class, 'index'])->name('interviews.index');
            Route::get('/interviews/selected', [App\Http\Controllers\Admin\InterviewController::class, 'selectedEmployees'])->name('interviews.selected');
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
            Route::post('/interviews/{interview}/welcome-letter', [App\Http\Controllers\Admin\InterviewController::class, 'sendWelcomeLetter'])->name('interviews.welcome-letter');
            Route::post('/interviews/{interview}/employment-details', [App\Http\Controllers\Admin\InterviewController::class, 'saveEmploymentDetails'])->name('interviews.employment-details');
            
            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'store'])->name('attendance.store');
            
            /*
            |--------------------------------------------------------------------------
            | SALARY MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/salary', [App\Http\Controllers\Admin\SalaryController::class, 'index'])->name('salary.index');
            Route::post('/salary/generate', [App\Http\Controllers\Admin\SalaryController::class, 'generate'])->name('salary.generate');
            Route::post('/salary/set-default-salaries', [App\Http\Controllers\Admin\SalaryController::class, 'setDefaultSalaries'])->name('salary.set-default');
            Route::get('/salary/{id}/view', [App\Http\Controllers\Admin\SalaryController::class, 'view'])->name('salary.view');
            Route::get('/salary/{id}/slip', [App\Http\Controllers\Admin\SalaryController::class, 'slip'])->name('salary.slip');
            Route::get('/salary/check-auto-generated', [App\Http\Controllers\Admin\SalaryController::class, 'checkAutoGenerated'])->name('salary.check-auto-generated');
            
            // Manual salary generation command trigger
            Route::post('/salary/auto-generate', function() {
                \Illuminate\Support\Facades\Artisan::call('salary:generate-monthly');
                return redirect()->route('admin.salary.index')->with('success', 'Monthly salary generation completed!');
            })->name('salary.auto-generate');
            
            /*
            |--------------------------------------------------------------------------
            | HR NOTES MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/hr-notes', [App\Http\Controllers\Admin\HrNoteController::class, 'index'])->name('hr-notes.index');
            Route::post('/hr-notes', [App\Http\Controllers\Admin\HrNoteController::class, 'store'])->name('hr-notes.store');
            Route::post('/hr-notes/{id}/status', [App\Http\Controllers\Admin\HrNoteController::class, 'updateStatus'])->name('hr-notes.status');
            Route::delete('/hr-notes/{id}', [App\Http\Controllers\Admin\HrNoteController::class, 'destroy'])->name('hr-notes.destroy');
            
            /*
            |--------------------------------------------------------------------------
            | BIRTHDAY MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/birthdays', [App\Http\Controllers\Admin\BirthdayController::class, 'index'])->name('birthdays.index');
            
            /*
            |--------------------------------------------------------------------------
            | BILL MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/bills', [App\Http\Controllers\Admin\BillController::class, 'index'])->name('bills.index');
            Route::post('/bills', [App\Http\Controllers\Admin\BillController::class, 'store'])->name('bills.store');
            Route::post('/bills/{id}/mark-paid', [App\Http\Controllers\Admin\BillController::class, 'markAsPaid'])->name('bills.mark-paid');
            Route::delete('/bills/{id}', [App\Http\Controllers\Admin\BillController::class, 'destroy'])->name('bills.destroy');
            Route::get('/bills/due-today', [App\Http\Controllers\Admin\BillController::class, 'getDueToday'])->name('bills.due-today');
            
            /*
            |--------------------------------------------------------------------------
            | EXPENSES MANAGEMENT (Admin's own expenses)
            |--------------------------------------------------------------------------
            */
            Route::get('/expenses', [App\Http\Controllers\Admin\ExpenseController::class, 'index'])->name('expenses.index');
            Route::post('/expenses', [App\Http\Controllers\Admin\ExpenseController::class, 'index'])->name('expenses.store');
            
            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE EXPENSES MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/employee-expenses', [App\Http\Controllers\Admin\ExpenseController::class, 'index'])->name('employee-expenses.index');
            Route::patch('/employee-expenses/{expense}/status', [App\Http\Controllers\Admin\ExpenseController::class, 'updateStatus'])->name('employee-expenses.update-status');
            
            /*
            |--------------------------------------------------------------------------
            | TICKETS MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/tickets', [App\Http\Controllers\Admin\TicketController::class, 'index'])->name('tickets.index');
            Route::post('/tickets/{id}/mark-viewed', [App\Http\Controllers\Admin\TicketController::class, 'markViewed'])->name('tickets.mark-viewed');
            Route::post('/tickets/{id}/respond', [App\Http\Controllers\Admin\TicketController::class, 'respond'])->name('tickets.respond');
            
            /*
            |--------------------------------------------------------------------------
            | JOB OPENING MANAGEMENT
            |--------------------------------------------------------------------------
            */
            Route::get('/job-openings', [App\Http\Controllers\Admin\JobOpeningController::class, 'index'])->name('job-openings.index');
            Route::get('/job-openings/create', [App\Http\Controllers\Admin\JobOpeningController::class, 'create'])->name('job-openings.create');
            Route::post('/job-openings', [App\Http\Controllers\Admin\JobOpeningController::class, 'store'])->name('job-openings.store');
            Route::get('/job-openings/{jobOpening}', [App\Http\Controllers\Admin\JobOpeningController::class, 'show'])->name('job-openings.show');
            Route::get('/job-openings/{jobOpening}/edit', [App\Http\Controllers\Admin\JobOpeningController::class, 'edit'])->name('job-openings.edit');
            Route::put('/job-openings/{jobOpening}', [App\Http\Controllers\Admin\JobOpeningController::class, 'update'])->name('job-openings.update');
            Route::delete('/job-openings/{jobOpening}', [App\Http\Controllers\Admin\JobOpeningController::class, 'destroy'])->name('job-openings.destroy');
            Route::post('/job-openings/{jobOpening}/close', [App\Http\Controllers\Admin\JobOpeningController::class, 'markClosed'])->name('job-openings.close');
            Route::post('/job-openings/{jobOpening}/activate', [App\Http\Controllers\Admin\JobOpeningController::class, 'markActive'])->name('job-openings.activate');
            Route::get('/job-openings-active', [App\Http\Controllers\Admin\JobOpeningController::class, 'getActiveJobs'])->name('job-openings.active');
            
            /*
            |--------------------------------------------------------------------------
            | NOTIFICATIONS
            |--------------------------------------------------------------------------
            */
            Route::get('/notifications/unread', [App\Http\Controllers\Admin\NotificationController::class, 'getUnread'])->name('notifications.unread');
            Route::post('/notifications/{id}/read', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
            Route::post('/notifications/read-all', [App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
            
            /*
            |--------------------------------------------------------------------------
            | PF FORMS
            |--------------------------------------------------------------------------
            */
            Route::get('/pf/forms', function () {
                return view('admin.pf.forms');
            })->name('pf.forms');
            
            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE CREDENTIALS
            |--------------------------------------------------------------------------
            */
            Route::get('/employee-credentials', [App\Http\Controllers\EmployeeCredentialsController::class, 'index'])->name('employee.credentials');
            Route::post('/employee/{id}/generate-password', [App\Http\Controllers\EmployeeCredentialsController::class, 'generatePassword'])->name('employee.generate-password');
            
            /*
            |--------------------------------------------------------------------------
            | ADMIN PROFILE & SETTINGS
            |--------------------------------------------------------------------------
            */
            Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'profile'])->name('profile');
            Route::get('/settings', [App\Http\Controllers\Admin\ProfileController::class, 'settings'])->name('settings');
            Route::post('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'updateProfile'])->name('profile.update');
            Route::post('/profile/change-password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.change-password');
        });

        /* globle search bar  */
        Route::get('/admin/global-search', [SearchController::class, 'globalSearch']);


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
                
                // Get employee documents status
                $documentsCount = \App\Models\EmployeeDocument::where('user_id', $user->id)->count();
                $pendingDocs = \App\Models\EmployeeDocument::where('user_id', $user->id)
                    ->where('status', 'pending')->count();
                $approvedDocs = \App\Models\EmployeeDocument::where('user_id', $user->id)
                    ->where('status', 'approved')->count();
                
                // Get attendance data
                $todayAttendance = \DB::table('attendance')
                    ->where('employee_id', $user->id)
                    ->where('attendance_date', date('Y-m-d'))
                    ->first();
                    
                $monthlyAttendance = \DB::table('attendance')
                    ->where('employee_id', $user->id)
                    ->whereMonth('attendance_date', date('m'))
                    ->whereYear('attendance_date', date('Y'))
                    ->where('status', 'Present')
                    ->count();
                
                // Birthday employees check
                $todayBirthdays = Employee::whereRaw('DATE_FORMAT(dob, "%m-%d") = ?', [date('m-d')])
                    ->where('dob', '!=', null)
                    ->get();

                return view('employee.dashboard', [
                    'user' => $user,
                    'documentsCount' => $documentsCount,
                    'pendingDocs' => $pendingDocs,
                    'approvedDocs' => $approvedDocs,
                    'todayAttendance' => $todayAttendance,
                    'monthlyAttendance' => $monthlyAttendance,
                    'todayBirthdays' => $todayBirthdays,
                ]);
            })->name('dashboard');
            
            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE TICKETS
            |--------------------------------------------------------------------------
            */
            Route::get('/tickets', [App\Http\Controllers\Employee\TicketController::class, 'index'])->name('tickets');
            Route::post('/tickets', [App\Http\Controllers\Employee\TicketController::class, 'store'])->name('tickets.store');
            
            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE EXPENSES
            |--------------------------------------------------------------------------
            */
            Route::get('/expenses', [App\Http\Controllers\Employee\ExpenseController::class, 'index'])->name('expenses.index');
            Route::get('/expenses/create', [App\Http\Controllers\Employee\ExpenseController::class, 'create'])->name('expenses.create');
            Route::post('/expenses', [App\Http\Controllers\Employee\ExpenseController::class, 'store'])->name('expenses.store');
            Route::get('/expenses/{expense}', [App\Http\Controllers\Employee\ExpenseController::class, 'show'])->name('expenses.show');

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

            Route::get('/dashboard', function() {
                // Birthday employees check
                $todayBirthdays = Employee::whereRaw('DATE_FORMAT(dob, "%m-%d") = ?', [date('m-d')])
                    ->where('dob', '!=', null)
                    ->get();
                    
                return view('client.dashboard', [
                    'user' => Auth::user(),
                    'todayBirthdays' => $todayBirthdays
                ]);
            })->name('dashboard');

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

/*------calcuter--------*/
Route::get('/salary-calculator', [SalaryCalculatorController::class, 'index'])
    ->name('admin.salary.calculator');

Route::post('/salary-calculator', [SalaryCalculatorController::class, 'calculate'])
    ->name('salary.calculate');

/*------test salary system--------*/
include __DIR__ . '/test-salary.php';

/*------debug gender--------*/
include __DIR__ . '/debug.php';

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});
