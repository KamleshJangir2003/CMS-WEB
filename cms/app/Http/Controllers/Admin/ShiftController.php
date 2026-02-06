<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    public function index()
    {
        $employees = Employee::where('user_type', 'employee')->get();

        return view('auth.admin.employees.employee_shift', compact('employees'));
    }

    public function store(Request $request)
    {
        Log::info('Shift Creation Request:', $request->all());

        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|exists:employees,id',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'shift_type' => 'required|in:Day,Night',
                'shift_date' => 'required|date',
                'break_start' => 'nullable|date_format:H:i',
                'break_end' => 'nullable|date_format:H:i',
                'assigned_by' => 'required|in:Admin,Manager,Supervisor',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $existingShift = Shift::where('employee_id', $request->employee_id)
                ->where('shift_date', $request->shift_date)
                ->first();

            if ($existingShift) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee already has a shift on this date',
                ], 400);
            }

            $start = strtotime($request->start_time);
            $end = strtotime($request->end_time);
            $totalMinutes = ($end - $start) / 60;

            if ($request->break_start && $request->break_end) {
                $breakStart = strtotime($request->break_start);
                $breakEnd = strtotime($request->break_end);
                $breakMinutes = ($breakEnd - $breakStart) / 60;
                $totalMinutes -= $breakMinutes;
            }

            $totalHours = round($totalMinutes / 60, 2);

            $shiftData = [
                'employee_id' => $request->employee_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'shift_type' => $request->shift_type,
                'shift_date' => $request->shift_date,
                'break_start' => $request->break_start,
                'break_end' => $request->break_end,
                'total_hours' => $totalHours,
                'status' => 'Scheduled',
                'assigned_by' => $request->assigned_by,
                'created_by' => Auth::id(),
            ];

            $shift = Shift::create($shiftData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Shift created successfully!',
                'shift' => $shift,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Shift creation error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating shift: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getShifts(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $query = Shift::with(['employee' => function ($q) {
                $q->select('id', 'first_name', 'last_name');
            }]);

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            if ($request->filled('start_time')) {
                $query->where('start_time', '>=', $request->start_time);
            }

            if ($request->filled('shift_type')) {
                $query->where('shift_type', $request->shift_type);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $shifts = $query->orderBy('shift_date', 'desc')
                ->orderBy('start_time', 'asc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json($shifts);

        } catch (\Exception $e) {
            Log::error('Error fetching shifts:', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'total' => 0,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $shift = Shift::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'shift_type' => 'required|in:Day,Night',
                'shift_date' => 'required|date',
                'break_start' => 'nullable|date_format:H:i',
                'break_end' => 'nullable|date_format:H:i',
                'status' => 'required|in:Scheduled,Completed,Cancelled',
                'assigned_by' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $start = strtotime($request->start_time);
            $end = strtotime($request->end_time);
            $totalMinutes = ($end - $start) / 60;

            if ($request->break_start && $request->break_end) {
                $breakStart = strtotime($request->break_start);
                $breakEnd = strtotime($request->break_end);
                $breakMinutes = ($breakEnd - $breakStart) / 60;
                $totalMinutes -= $breakMinutes;
            }

            $totalHours = round($totalMinutes / 60, 2);

            $shift->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'shift_type' => $request->shift_type,
                'shift_date' => $request->shift_date,
                'break_start' => $request->break_start,
                'break_end' => $request->break_end,
                'total_hours' => $totalHours,
                'status' => $request->status,
                'assigned_by' => $request->assigned_by,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shift updated successfully!',
                'shift' => $shift,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shift deleted successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $shift = Shift::with('employee')->findOrFail($id);

            return response()->json($shift);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Shift not found',
            ], 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $shift = Shift::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Scheduled,Completed,Cancelled',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $shift->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Shift status updated!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }
}