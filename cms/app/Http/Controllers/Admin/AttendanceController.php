<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\SalaryRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $view_type = $request->get('view_type', 'daily');
        $selected_date = $request->get('date', date('Y-m-d'));
        $selected_shift = $request->get('shift', 'Day');
        $selected_week = $request->get('week', date('Y-\WW'));
        $selected_month = $request->get('month', date('Y-m'));
        $department_filter = $request->get('department', '');
        $search_employee = $request->get('search', '');

        // Get departments for filter
        $departments = Employee::where('user_type', 'employee')
            ->distinct()
            ->pluck('department')
            ->filter();

        // Build employee query with filters
        $query = Employee::where('user_type', 'employee');

        if ($department_filter) {
            $query->where('department', $department_filter);
        }

        if ($search_employee) {
            $query->where(function($q) use ($search_employee) {
                $q->where('first_name', 'like', "%$search_employee%")
                  ->orWhere('last_name', 'like', "%$search_employee%")
                  ->orWhere('email', 'like', "%$search_employee%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search_employee%"]);
            });
        }

        // Order employees with recent attendance first, then by name
        $employees = $query->leftJoin('attendance', function($join) use ($selected_date, $selected_shift) {
                $join->on('employees.id', '=', 'attendance.employee_id')
                     ->where('attendance.attendance_date', '=', $selected_date)
                     ->where('attendance.shift', '=', $selected_shift);
            })
            ->select('employees.*', 'attendance.created_at as attendance_created_at')
            ->orderByDesc('attendance.created_at')
            ->orderBy('employees.first_name')
            ->get();
        $attendance_data = [];
        $attendance_summary = [];

        if ($view_type === 'daily') {
            // Daily view - existing logic with shift
            if ($employees->count() > 0) {
                $employee_ids = $employees->pluck('id')->toArray();
                $attendance_records = DB::table('attendance')
                    ->whereIn('employee_id', $employee_ids)
                    ->where('attendance_date', $selected_date)
                    ->where('shift', $selected_shift)
                    ->get();

                foreach ($attendance_records as $att) {
                    $attendance_data[$att->employee_id] = $att;
                }
            }
        } elseif ($view_type === 'weekly') {
            // Weekly view
            $year = substr($selected_week, 0, 4);
            $week = substr($selected_week, 6, 2);
            $start_date = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $end_date = $start_date->copy()->endOfWeek();
            
            $attendance_summary = $this->getAttendanceSummary($employees, $start_date, $end_date);
        } elseif ($view_type === 'monthly') {
            // Monthly view
            $start_date = Carbon::createFromFormat('Y-m', $selected_month)->startOfMonth();
            $end_date = $start_date->copy()->endOfMonth();
            
            $attendance_summary = $this->getAttendanceSummary($employees, $start_date, $end_date);
        }

        return view('admin.attendance.index', compact(
            'employees', 
            'attendance_data', 
            'attendance_summary',
            'selected_date', 
            'selected_shift',
            'selected_week',
            'selected_month',
            'view_type',
            'departments', 
            'department_filter', 
            'search_employee'
        ));
    }

    private function getAttendanceSummary($employees, $start_date, $end_date)
    {
        $summary = [];
        
        foreach ($employees as $emp) {
            $attendance_records = DB::table('attendance')
                ->where('employee_id', $emp->id)
                ->whereBetween('attendance_date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
                ->get();
            
            $present = $attendance_records->where('status', 'Present')->count();
            $absent = $attendance_records->where('status', 'Absent')->count();
            $half_day = $attendance_records->where('status', 'Half Day')->count();
            $paid_leave = $attendance_records->where('status', 'Paid Leave')->count();
            $comp_off = $attendance_records->where('status', 'Comp Off')->count();
            $unauthorized_leave = $attendance_records->where('status', 'Unauthorized Leave')->count();
            $holiday = $attendance_records->where('status', 'Holiday')->count();
            $week_off = $attendance_records->where('status', 'Week Off')->count();
            
            // Calculate total excluding Absent and Unauthorized Leave
            $total = $present + ($half_day * 0.5) + $paid_leave + $comp_off + $holiday + $week_off;
            
            $summary[] = [
                'name' => $emp->first_name . ' ' . $emp->last_name,
                'department' => $emp->department ?? 'N/A',
                'present' => $present,
                'absent' => $absent,
                'half_day' => $half_day,
                'paid_leave' => $paid_leave,
                'comp_off' => $comp_off,
                'unauthorized_leave' => $unauthorized_leave,
                'holiday' => $holiday,
                'week_off' => $week_off,
                'total' => $total
            ];
        }
        
        return $summary;
    }

    private function checkAndGenerateSalary($attendance_date)
    {
        $date = Carbon::parse($attendance_date);
        
        // Always check if it's month end or if we're in a new month
        if ($date->isLastOfMonth() || $date->day >= 28) {
            $month = $date->month;
            $year = $date->year;
            
            // Check if salary already generated for this month
            $existingSalaries = SalaryRecord::where('month', $month)
                ->where('year', $year)
                ->count();
                
            if ($existingSalaries == 0) {
                // Generate salary for all employees
                $generatedCount = $this->generateMonthlySalary($month, $year);
                
                if ($generatedCount > 0) {
                    // Store notification for admin
                    session()->flash('salary_generated', [
                        'count' => $generatedCount,
                        'month' => $month,
                        'year' => $year,
                        'month_name' => Carbon::create($year, $month)->format('F')
                    ]);
                }
            }
        }
    }
    
    private function generateMonthlySalary($month, $year)
    {
        $employees = Employee::where('user_type', 'employee')
            ->whereNotNull('in_hand_salary')
            ->where('in_hand_salary', '>', 0)
            ->get();

        $totalDaysInMonth = Carbon::create($year, $month)->daysInMonth;
        $generatedCount = 0;

        foreach ($employees as $employee) {
            // Check if salary already exists for this employee
            $existingRecord = SalaryRecord::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();
                
            if ($existingRecord) {
                continue; // Skip if already generated
            }

            $inHandSalary = $employee->in_hand_salary;
            
            // Calculate salary components using same logic as salary calculator
            $gross = $this->calculateGrossFromInHand($inHandSalary);
            $basic = $gross * 0.60;
            $hra = $gross * 0.40;
            
            // PF calculations with cap rule
            $pfBasic = ($basic >= 15000) ? 15000 : $basic;
            $employeePf = $pfBasic * 0.12;
            $employerPf = $pfBasic * 0.13;
            
            // ESIC calculations (only if Gross <= 21000)
            if ($gross <= 21000) {
                $employeeEsic = $gross * 0.0075;
                $employerEsic = $gross * 0.0325;
            } else {
                $employeeEsic = 0;
                $employerEsic = 0;
            }

            $attendanceRecords = DB::table('attendance')
                ->where('employee_id', $employee->id)
                ->whereYear('attendance_date', $year)
                ->whereMonth('attendance_date', $month)
                ->get();

            $present = $attendanceRecords->where('status', 'Present')->count();
            $absent = $attendanceRecords->where('status', 'Absent')->count();
            $halfDay = $attendanceRecords->where('status', 'Half Day')->count();
            $unauthorizedLeave = $attendanceRecords->where('status', 'Unauthorized Leave')->count();
            $paidLeave = $attendanceRecords->where('status', 'Paid Leave')->count();
            $holiday = $attendanceRecords->where('status', 'Holiday')->count();
            $weekOff = $attendanceRecords->where('status', 'Week Off')->count();
            $compOff = $attendanceRecords->where('status', 'Comp Off')->count();

            // Calculate working days
            $workingDays = $present + $paidLeave + $compOff + ($halfDay * 0.5);

            // Calculate per day salary
            $perDaySalary = $inHandSalary / $totalDaysInMonth;

            // Calculate salary based on working days only (proportional)
            $earnedSalary = $workingDays * $perDaySalary;
            
            // No deductions - just pay for actual working days
            $deduction = $inHandSalary - $earnedSalary;
            $netSalary = $earnedSalary;

            SalaryRecord::create([
                'employee_id' => $employee->id,
                'month' => $month,
                'year' => $year,
                'basic_salary' => $inHandSalary,
                'working_days' => $workingDays,
                'deduction' => $deduction,
                'advance' => 0,
                'incentive' => 0,
                'employee_pf' => $employeePf,
                'employee_esi' => $employeeEsic,
                'employer_pf' => $employerPf,
                'employer_esi' => $employerEsic,
                'net_salary' => $netSalary,
                'shift' => $employee->shift ?? 'Day'
            ]);
            
            $generatedCount++;
        }
        
        return $generatedCount;
    }
    
    private function calculateGrossFromInHand($inHand)
    {
        // Iterative approach to find gross that results in desired in-hand
        $gross = $inHand;
        
        for ($i = 0; $i < 10; $i++) {
            $basic = $gross * 0.60;
            $pfBasic = ($basic >= 15000) ? 15000 : $basic;
            $employeePf = $pfBasic * 0.12;
            
            $employeeEsic = ($gross <= 21000) ? $gross * 0.0075 : 0;
            
            $calculatedInHand = $gross - $employeePf - $employeeEsic;
            
            if (abs($calculatedInHand - $inHand) < 0.01) {
                break;
            }
            
            $gross = $gross + ($inHand - $calculatedInHand);
        }
        
        return round($gross, 2);
    }

    public function store(Request $request)
    {
        $attendance_date = $request->attendance_date;
        $shift = $request->shift ?? 'Day';
        
        foreach ($request->employees as $employee_id => $data) {
            $status = $data['status'];
            
            // Skip if no status selected
            if (empty($status)) {
                continue;
            }
            
            $in_time = !empty($data['in_time']) ? $data['in_time'] : null;
            $out_time = !empty($data['out_time']) ? $data['out_time'] : null;
            $reason = $data['reason'];
            
            // Use upsert to handle duplicates
            DB::table('attendance')->updateOrInsert(
                [
                    'employee_id' => $employee_id,
                    'attendance_date' => $attendance_date,
                    'shift' => $shift
                ],
                [
                    'status' => $status,
                    'in_time' => $in_time,
                    'out_time' => $out_time,
                    'reason' => $reason,
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );
        }
        
        // Check if month is complete and auto-generate salary
        $this->checkAndGenerateSalary($attendance_date);
        
        $message = 'Attendance saved successfully!';
        
        // Check if salary was generated
        if (session()->has('salary_generated')) {
            $salaryData = session('salary_generated');
            $message .= " Additionally, salary for {$salaryData['count']} employees has been automatically generated for {$salaryData['month_name']} {$salaryData['year']}. Check the salary page for details.";
        }
        
        return redirect()->back()->with('success', $message);
    }
}