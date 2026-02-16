@extends('auth.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
.dashboard-wrapper{
    padding: 25px;
    margin-left: 130px;
    margin-top: 60px;
}

/* Mobile Dashboard Responsive */
@media (max-width: 768px) {
    .dashboard-wrapper {
        margin-left: 0 !important;
        margin-top: 70px;
        padding: 15px;
    }
    
    .stat-card {
        min-height: 100px;
    }
    
    .stat-number {
        font-size: 24px !important;
    }
    
    .stat-title {
        font-size: 12px;
    }
    
    .alert {
        padding: 12px !important;
        margin-bottom: 15px !important;
    }
    
    .alert h5 {
        font-size: 16px;
    }
    
    .table-responsive {
        font-size: 12px;
    }
    
    .btn-sm {
        font-size: 10px;
        padding: 4px 8px;
    }
}
.stat-card{
    border: none;
    border-radius: 14px;
    color: #fff;
    position: relative;
    overflow: hidden;
    min-height: 130px;
}
.stat-card i{
    position: absolute;
    right: 20px;
    bottom: 20px;
    font-size: 45px;
    opacity: 0.3;
}
.stat-title{
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.stat-number{
    font-size: 32px;
    font-weight: 700;
}
.table-card{
    border-radius: 14px;
    border: none;
}
.table thead{
    background: #f5f6fa;
}
.badge-role{
    padding: 6px 10px;
    font-size: 12px;
}
.welcome-card{
    background: linear-gradient(135deg, #4f46e5, #3b82f6);
    color: #fff;
    border-radius: 16px;
}

.birthday-alert {
    animation: slideInDown 0.8s ease-out;
}

.hiring-alert {
    animation: slideInDown 0.8s ease-out;
}

.bill-alert {
    animation: slideInDown 0.8s ease-out;
}

@keyframes slideInDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<div class="dashboard-wrapper">

    <!-- 🎉 Birthday Alert -->
    @if(isset($todayBirthdays) && $todayBirthdays->count() > 0)
    <div class="alert alert-info birthday-alert mb-4" style="background: linear-gradient(135deg, #ff6b6b, #ffa500); color: white; border: none; border-radius: 12px;">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-birthday-cake fa-2x me-3"></i>
            <div>
                <h5 class="mb-1">🎉 Today's Birthdays!</h5>
                <p class="mb-0">
                    @foreach($todayBirthdays as $employee)
                        <strong>{{ $employee->full_name }}</strong> ({{ $employee->department }})@if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- 🔥 Hiring Required Alert -->
    @if(isset($activeJobOpenings) && $activeJobOpenings->count() > 0)
    <div class="alert alert-warning hiring-alert mb-4" style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; border: none; border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-briefcase fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">🔥 Hiring Required</h5>
                    <p class="mb-0">{{ $activeJobOpenings->count() }} active job opening(s) need attention</p>
                </div>
            </div>
            <button class="btn btn-light btn-sm" onclick="showHiringModal()">
                <i class="fa-solid fa-eye"></i> View Details
            </button>
        </div>
    </div>
    @endif

    <!-- 💰 Due Bills Alert -->
    <div id="dueBillsAlert" class="alert alert-warning bill-alert mb-4" style="background: linear-gradient(135deg, #f39c12, #e74c3c); color: white; border: none; border-radius: 12px; display: none;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-file-invoice fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">💰 Plaese Paid Your billing !</h5>
                    <p class="mb-0" id="dueBillsList">Loading bills...</p>
                </div>
            </div>
            <!-- <button class="btn btn-light btn-sm" onclick="showBillsModal()">
                <i class="fa-solid fa-eye"></i> View Details
            </button> -->
        </div>
    </div>

    <!-- 💸 Auto-Generated Salary Alert -->
    <div id="autoSalaryAlert" class="alert alert-success bill-alert mb-4" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; border-radius: 12px; display: none;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-money-bill-wave fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">💸 Monthly Salary Generated!</h5>
                    <p class="mb-0" id="autoSalaryText">Checking for auto-generated salaries...</p>
                </div>
            </div>
            <button class="btn btn-light btn-sm" onclick="viewSalaryRecords()">
                <i class="fa-solid fa-eye"></i> View Salary
            </button>
        </div>
    </div>

    <!-- 📞 Today's Callbacks Alert -->
    @if(isset($todayCallbacks) && $todayCallbacks->count() > 0)
    <div class="alert alert-warning bill-alert mb-4" style="background: linear-gradient(135deg, #ff9500, #ff6b35); color: white; border: none; border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-phone fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">📞 आज Callback करना है!</h5>
                    <p class="mb-0">
                        @foreach($todayCallbacks as $callback)
                            <strong>{{ $callback->name }}</strong>@if(!$loop->last), @endif
                        @endforeach
                    </p>
                </div>
            </div>
            <a href="{{ url('/admin/callbacks') }}" class="btn btn-light btn-sm">
                <i class="fa-solid fa-eye"></i> View Callbacks
            </a>
        </div>
    </div>
    @endif
    

    <!-- 🔹 Stats Cards -->
    <div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="stat-title">Employee Leads</div>
                    <div class="stat-number">{{ $stats['totalLeads'] ?? 0 }}</div>
                    <i class="bi bi-person-plus-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-primary">
                <div class="card-body">
                    <div class="stat-title">Total Employees</div>
                    <div class="stat-number">{{ $stats['totalEmployees'] ?? 0 }}</div>
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="stat-title">Pending Approvals</div>
                    <div class="stat-number">{{ $stats['pendingApprovals'] ?? 0 }}</div>
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>

        <!-- <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="stat-title">Total Admins</div>
                    <div class="stat-number">{{ $stats['totalAdmins'] ?? 0 }}</div>
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
        </div> -->

        <!-- <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="stat-title">Total Clients</div>
                    <div class="stat-number">{{ $stats['totalClients'] ?? 0 }}</div>
                    <i class="bi bi-briefcase-fill"></i>
                </div>
            </div>
        </div> -->
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="stat-title">New Callbacks</div>
                    <div class="stat-number">{{ $stats['totalCallbacks'] ?? 0 }}</div>
                    <i class="bi bi-telephone-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="stat-title">Total Employee Interviews</div>
                    <div class="stat-number">{{ $stats['totalInterviews'] ?? 0 }}</div>
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-danger">
                <div class="card-body">
                    <div class="stat-title">Total Employee rejected Interviews</div>
                    <div class="stat-number">{{ $stats['rejectedInterviews'] ?? 0 }}</div>
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="stat-title">New Tickets</div>
                    <div class="stat-number">{{ $stats['newTickets'] ?? 0 }}</div>
                    <i class="bi bi-ticket-perforated"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-secondary">
                <div class="card-body">
                    <div class="stat-title">Total Tickets</div>
                    <div class="stat-number">{{ $stats['totalTickets'] ?? 0 }}</div>
                    <i class="bi bi-ticket-detailed"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="{{ url('/admin/leads/interested') }}" class="text-decoration-none">
                <div class="card stat-card bg-success">
                    <div class="card-body">
                        <div class="stat-title">Interested</div>
                        <div class="stat-number">{{ $stats['interested'] ?? 0 }}</div>
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-danger">
                <div class="card-body">
                    <div class="stat-title"> Interviews Schedule</div>
                    <div class="stat-number">{{ $stats['scheduledInterviews'] ?? 0 }}</div>
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning">
                <div class="card-body">
                    <div class="stat-title">Employee Hired</div>
                    <div class="stat-number">{{ $stats['employeeHired'] ?? 0 }}</div>
                    <i class="bi bi-ticket-perforated"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-secondary">
                <div class="card-body">
                    <div class="stat-title">Selected Employee</div>
                    <div class="stat-number">{{ $stats['selectedEmployee'] ?? 0 }}</div>
                    <i class="bi bi-ticket-detailed"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 Pending Approvals Table -->
    @if(isset($pendingUsers) && $pendingUsers->count() > 0)
    <div class="card table-card mb-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Pending User Approvals</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingUsers as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->department }}</td>
                            <td>
                                <span class="badge bg-secondary badge-role">
                                    {{ ucfirst($user->user_type) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.employees.details', $user->id) }}" class="btn btn-sm btn-info me-2">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                                <form action="{{ route('admin.approve', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> Approve
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- 🔹 Welcome Card -->
    <!-- <div class="card welcome-card">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="mb-1">Welcome, {{ Auth::user()->first_name }} 👋</h4>
                <p class="mb-0">You are logged in as <strong>Administrator</strong></p>
            </div>
            <a href="{{ route('admin.users') }}" class="btn btn-light mt-3 mt-md-0">
                <i class="bi bi-gear"></i> Manage Users
            </a>
        </div>
    </div> -->

</div>

<!-- Hiring Required Modal -->
<div class="modal fade" id="hiringModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #e74c3c, #c0392b); color: white;">
                <h5 class="modal-title">
                    <i class="fa-solid fa-briefcase me-2"></i>Active Job Openings - Hiring Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if(isset($activeJobOpenings) && $activeJobOpenings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Shift</th>
                                    <th>Salary</th>
                                    <th>Days Since Posted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeJobOpenings as $job)
                                <tr>
                                    <td><strong>{{ $job->job_title }}</strong></td>
                                    <td><span class="badge bg-info">{{ $job->shift }}</span></td>
                                    <td>₹{{ number_format($job->salary, 2) }}</td>
                                    <td>{{ $job->created_at->diffInDays() }} days</td>
                                    <td>
                                        <a href="{{ route('admin.job-openings.show', $job) }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                        <form action="{{ route('admin.job-openings.close', $job) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('Mark this job as hired/closed?')">
                                                <i class="fa-solid fa-check"></i> Close
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('admin.job-openings.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-cog"></i> Manage Job Openings
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Due Bills Modal -->
<div class="modal fade" id="dueBillsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f39c12, #e74c3c); color: white;">
                <h5 class="modal-title">
                    <i class="fa-solid fa-file-invoice me-2"></i>Bills Due Today
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalBillsContent">
                    <!-- Bills will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('admin.bills.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-cog"></i> Manage Bills
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showHiringModal() {
    const modal = new bootstrap.Modal(document.getElementById('hiringModal'));
    modal.show();
}

// Check for due bills on dashboard load
document.addEventListener('DOMContentLoaded', function() {
    checkDueBillsForDashboard();
    checkAutoGeneratedSalary();
});

function checkAutoGeneratedSalary() {
    fetch('/admin/salary/check-auto-generated')
        .then(response => response.json())
        .then(data => {
            if (data.hasAutoGenerated && data.salaryData) {
                showAutoSalaryAlert(data.salaryData);
            }
        })
        .catch(error => console.error('Error checking auto-generated salary:', error));
}

function showAutoSalaryAlert(salaryData) {
    const alert = document.getElementById('autoSalaryAlert');
    const salaryText = document.getElementById('autoSalaryText');
    
    salaryText.textContent = `Salary for ${salaryData.count} employees has been automatically generated for ${salaryData.month_name} ${salaryData.year}.`;
    alert.style.display = 'block';
    
    // Store salary data for navigation
    window.autoSalaryData = salaryData;
}

function viewSalaryRecords() {
    if (window.autoSalaryData) {
        window.location.href = `/admin/salary?month=${window.autoSalaryData.month}&year=${window.autoSalaryData.year}`;
    } else {
        window.location.href = '/admin/salary';
    }
}

function checkDueBillsForDashboard() {
    fetch('/admin/bills/due-today')
        .then(response => response.json())
        .then(data => {
            if (data.bills && data.bills.length > 0) {
                showDueBillsAlert(data.bills);
            }
        })
        .catch(error => console.error('Error:', error));
}

function showDueBillsAlert(bills) {
    const alert = document.getElementById('dueBillsAlert');
    const billsList = document.getElementById('dueBillsList');
    
    let billsText = '';
    bills.forEach((bill, index) => {
        billsText += `${bill.bill_type} (₹${parseFloat(bill.amount).toFixed(2)})`;
        if (index < bills.length - 1) billsText += ', ';
    });
    
    billsList.textContent = billsText;
    alert.style.display = 'block';
    
    // Store bills data for modal
    window.dueBillsData = bills;
}

function showBillsModal() {
    if (window.dueBillsData) {
        let content = '<div class="table-responsive">';
        content += '<table class="table table-hover">';
        content += '<thead><tr><th>Bill Type</th><th>Amount</th><th>Due Date</th><th>Action</th></tr></thead><tbody>';
        
        window.dueBillsData.forEach(function(bill) {
            content += `<tr>
                <td><strong>${bill.bill_type}</strong></td>
                <td>₹${parseFloat(bill.amount).toFixed(2)}</td>
                <td>${new Date(bill.due_date).toLocaleDateString('en-GB')}</td>
                <td>
                    <button class="btn btn-sm btn-success" onclick="markBillAsPaidFromDashboard(${bill.id})">
                        <i class="fa-solid fa-check"></i> Mark Paid
                    </button>
                </td>
            </tr>`;
        });
        
        content += '</tbody></table></div>';
        document.getElementById('modalBillsContent').innerHTML = content;
        
        const modal = new bootstrap.Modal(document.getElementById('dueBillsModal'));
        modal.show();
    }
}

function markBillAsPaidFromDashboard(billId) {
    fetch(`/admin/bills/${billId}/mark-paid`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove bill from data
            window.dueBillsData = window.dueBillsData.filter(bill => bill.id !== billId);
            
            // If no bills left, hide alert
            if (window.dueBillsData.length === 0) {
                document.getElementById('dueBillsAlert').style.display = 'none';
                bootstrap.Modal.getInstance(document.getElementById('dueBillsModal')).hide();
            } else {
                // Update alert and modal
                showDueBillsAlert(window.dueBillsData);
                showBillsModal();
            }
        } else {
            alert('Error marking bill as paid');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error marking bill as paid');
    });
}
</script>
@endsection
