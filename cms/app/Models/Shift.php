<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    use HasFactory;

    // Mass assignment के लिए fields
    protected $fillable = [
        'employee_id',
        'start_time',
        'end_time',
        'shift_type',
        'shift_date',
        'break_start',
        'break_end',
        'total_hours',
        'status',
        'assigned_by',
        'created_by',
    ];

    // Casting for dates and times
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
        'shift_date' => 'date',
        'total_hours' => 'decimal:2',
    ];

    // Date fields
    protected $dates = ['shift_date'];

    // Default values
    protected $attributes = [
        'status' => 'Scheduled',
    ];

    /**
     * Get the employee that owns the shift.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the user who created the shift.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    /**
     * Calculate total hours worked.
     */
    public function calculateTotalHours(): float
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $totalMinutes = ($end - $start) / 60;

        if ($this->break_start && $this->break_end) {
            $breakStart = strtotime($this->break_start);
            $breakEnd = strtotime($this->break_end);
            $breakMinutes = ($breakEnd - $breakStart) / 60;
            $totalMinutes -= $breakMinutes;
        }

        return round($totalMinutes / 60, 2);
    }

    /**
     * Scope for active shifts.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'Scheduled');
    }

    /**
     * Scope for completed shifts.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Scope for upcoming shifts.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('shift_date', '>=', now()->toDateString())
            ->where('status', 'Scheduled');
    }
}
