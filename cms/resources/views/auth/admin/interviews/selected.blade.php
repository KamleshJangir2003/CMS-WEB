@extends('auth.layouts.app')
<style>
    .action-buttons {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
}

.action-btn {
    border-radius: 20px;
    font-size: 12px;
    padding: 4px 10px;
}

.dropdown-menu {
    border-radius: 10px;
    padding: 6px 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.dropdown-item {
    font-size: 13px;
    padding: 8px 14px;
}

.dropdown-item:hover {
    background-color: #f1fdf4;
}

</style>
@section('content')
<div class="main-content">
    <div class="page-header">
        <h1>✅ Selected Employees</h1>
        <a href="{{ route('admin.interviews.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Interviews
        </a>
    </div>

    <div class="content-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                    <th>Employee Name</th>
                        <th>Job Role</th>
                        <th>Interview Round</th>
                        <th>Selected Date</th>
                        <th>Interviewer</th>
                        
                        <th>Contact</th>
                        <th>Joining Date</th>
                        
                     
                        <th>CTC</th>
                        <th>In Hand</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($selectedInterviews as $interview)
                        @php
                            $employee = \App\Models\Employee::where('email', $interview->candidate_email)->first();
                        @endphp
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $interview->candidate_name }}</strong><br>
                                    <small class="text-muted">ID: #{{ $interview->lead_id }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="job-role">{{ $interview->job_role }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $interview->interview_round }}</span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ formatDate($interview->updated_at) }}</strong><br>
                                    <small>{{ $interview->updated_at->format('g:i A') }}</small>
                                </div>
                            </td>
                            <td>{{ $interview->interviewer }}</td>
                            <td>
                                <div>
                                    <small> {{ $interview->candidate_email }}</small><br>
                                    @if($interview->lead && $interview->lead->number)
                                        <small> {{ $interview->lead->number }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($employee && $employee->joining_date)
                                    <span class="text-success" >{{ formatDate($employee->joining_date) }}</span>
                                @else
                                    <input type="date" class="form-control form-control-sm" 
                                           id="joining_date_{{ $interview->id }}" 
                                           style="width: 140px; font-size: 12px;">
                                @endif
                            </td>
                            <td>
                                @if($employee && $employee->current_ctc)
                                    <span class="text-success">₹{{ number_format($employee->current_ctc) }}</span>
                                @else
                                    <input type="number" class="form-control form-control-sm" 
                                           id="current_ctc_{{ $interview->id }}" 
                                           placeholder="CTC" 
                                           style="width: 100px; font-size: 12px;">
                                @endif
                            </td>
                            <td>
                                @if($employee && $employee->in_hand_salary)
                                    <span class="text-success">₹{{ number_format($employee->in_hand_salary) }}</span>
                                @else
                                    <input type="number" class="form-control form-control-sm" 
                                           id="in_hand_salary_{{ $interview->id }}" 
                                           placeholder="In Hand" 
                                           style="width: 100px; font-size: 12px;">
                                @endif
                            </td>
                            <td>
    <div class="action-buttons">
        <span class="badge badge-success">✅ Selected</span>

        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle action-btn"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                Actions
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                @if(!$employee || !$employee->joining_date || !$employee->current_ctc || !$employee->in_hand_salary)
                    <li>
                        <a class="dropdown-item"
                           href="javascript:void(0)"
                           onclick="saveEmploymentDetails({{ $interview->id }})">
                            <i class="fas fa-save me-2 text-success"></i> Save Details
                        </a>
                    </li>
                @endif

                <li>
                    <a class="dropdown-item"
                       href="javascript:void(0)"
                       onclick="sendWelcomeLetter({{ $interview->id }})">
                        <i class="fas fa-envelope me-2 text-primary"></i> Send Welcome Letter
                    </a>
                </li>
            </ul>
        </div>
    </div>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No Selected Employees</h5>
                                    <p class="text-muted">No employees have been selected from interviews yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $selectedInterviews->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-header h1 {
    margin: 0;
    font-size: 1.5rem;
    color: #28a745;
}

.content-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow-x: auto;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
   
    margin-bottom: 0;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px 10px;
    font-size: 13px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    white-space: nowrap;
}

.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    display: inline-block;
}

.badge-info { background-color: #28a745 !important; color: white !important; }
.badge-success { background-color: #28a745; color: white; }

.job-role {
    font-weight: 500;
    color: #495057;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
    transform: translateY(-1px);
}

.empty-state {
    padding: 40px 20px;
    text-align: center;
}

.empty-state i {
    opacity: 0.3;
}

.empty-state h5 {
    margin-bottom: 10px;
    color: #6c757d;
}

.text-muted {
    color: #6c757d !important;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-start;
}

.btn-primary {
    background-color: #2eacb3;
    color: white;
    font-size: 11px;
    padding: 4px 8px;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-sm {
    font-size: 11px;
    padding: 4px 8px;
}

.btn-success {
    background-color: #28a745;
    color: white;
    font-size: 11px;
    padding: 4px 8px;
}

.btn-success:hover {
    background-color: #1e7e34;
}

.form-control-sm {
    padding: 2px 6px;
    font-size: 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.text-success {
    color: #28a745 !important;
    font-weight: 500;
}
</style>
@endsection

@section('scripts')
<script>
function saveEmploymentDetails(interviewId) {
    const joiningDate = document.getElementById(`joining_date_${interviewId}`).value;
    const currentCtc = document.getElementById(`current_ctc_${interviewId}`).value;
    const inHandSalary = document.getElementById(`in_hand_salary_${interviewId}`).value;
    
    if (!joiningDate || !currentCtc || !inHandSalary) {
        alert('Please fill all employment details (Joining Date, CTC, and In Hand Salary)');
        return;
    }
    
    if (confirm('Save employment details for this employee?')) {
        fetch(`/admin/interviews/${interviewId}/employment-details`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                joining_date: joiningDate,
                current_ctc: currentCtc,
                in_hand_salary: inHandSalary
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Employment details saved successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error saving employment details');
            console.error(error);
        });
    }
}

function sendWelcomeLetter(interviewId) {
    const joiningDate = prompt('Enter joining date (MM-DD-YYYY):');
    
    if (!joiningDate) return;
    
    if (confirm('Send welcome letter to this employee?')) {
        fetch(`/admin/interviews/${interviewId}/welcome-letter`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ joining_date: joiningDate })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Welcome letter sent successfully! Employee has been moved to Documents section.');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error sending welcome letter');
            console.error(error);
        });
    }
}
</script>
@endsection