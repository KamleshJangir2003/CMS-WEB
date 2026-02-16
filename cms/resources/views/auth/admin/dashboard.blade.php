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
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: linear-gradient(145deg, #f0f2f5 0%, #e6e9f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Main Card */
        .employee-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            width: 100%;
            max-width: 400px;
            border-radius: 32px;
            box-shadow: 
                0 20px 35px -8px rgba(0, 0, 0, 0.2),
                0 8px 18px -6px rgba(0, 0, 0, 0.1),
                inset 0 1px 1px rgba(255, 255, 255, 0.7);
            padding: 28px 24px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: transform 0.2s ease;
        }

        .employee-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 40px -10px rgba(0, 0, 0, 0.3);
        }

        /* Header */
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #eaeef5;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .card-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e2b3c;
            letter-spacing: -0.3px;
        }

        .icon-badge {
            background: #1e3a5f;
            color: white;
            font-size: 1.2rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 10px rgba(0, 50, 100, 0.2);
        }

        /* Row styling */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 10px 0;
            border-bottom: 1px dashed #d0d9e8;
        }

        .info-row:last-of-type {
            border-bottom: none;
        }

        .label {
            font-size: 1.1rem;
            font-weight: 500;
            color: #3d4e66;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .label span {
            font-size: 1.2rem;
        }

        .value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0b1f33;
        }

        .value small {
            font-size: 0.9rem;
            font-weight: 400;
            color: #5e6f88;
            margin-left: 5px;
        }

        /* Total row special */
        .total-row {
            background: #f4f7fd;
            margin: 4px 0 12px 0;
            padding: 14px 16px;
            border-radius: 24px;
            border: 1px solid #cfdcec;
        }

        .total-row .label {
            font-weight: 600;
            color: #022b49;
        }

        .total-row .value {
            font-size: 1.8rem;
            color: #003057;
        }

        /* Progress bar mini */
        .progress-section {
            margin: 18px 0 8px 0;
            background: #ecf1f7;
            border-radius: 30px;
            padding: 12px 15px;
        }

        .progress-item {
            margin-bottom: 12px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }

        .progress-bar-bg {
            width: 100%;
            height: 8px;
            background: #dbe1ec;
            border-radius: 30px;
            overflow: hidden;
        }

        .progress-fill-male {
            height: 8px;
            width: 73%;
            background: linear-gradient(90deg, #2563eb, #3898ff);
            border-radius: 30px;
        }

        .progress-fill-female {
            height: 8px;
            width: 23%;
            background: linear-gradient(90deg, #d43f8d, #f472b6);
            border-radius: 30px;
        }

        /* Footer summary */
        .footer-note {
            background: #e9edf4;
            text-align: center;
            padding: 12px;
            border-radius: 40px;
            margin-top: 20px;
            font-size: 0.95rem;
            color: #1f3a5f;
            font-weight: 500;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }

        .footer-note strong {
            font-weight: 700;
            color: #0e2b4b;
        }

        /* Responsive */
        @media (max-width: 450px) {
            .employee-card {
                padding: 20px 16px;
            }
            .card-header h2 {
                font-size: 1.3rem;
            }
        }
    </style>
    <style>
        /* ===============================
   TABLE CARD DESIGN
================================ */

.table-card {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    border: none;
    overflow: hidden;
    max-width: 700px;
}

/* Header */
.table-card .card-header {
   
    border-bottom: 1px solid #f1f1f1;
    background: #ffffff;
}

.table-card .card-header h5 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

/* Table */
.table-card table {
    margin-bottom: 0;
}

.table-card thead {
    background: #f8f9fc;
}

.table-card thead th {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #666;
    font-weight: 600;
    
    border-bottom: 1px solid #eaeaea;
}

/* Table Body */
.table-card tbody td {
    
    font-size: 14px;
    color: #444;
    border-bottom: 1px solid #f5f5f5;
}

.table-card tbody tr:last-child td {
    border-bottom: none;
}

/* Row Hover */
.table-card tbody tr {
    transition: 0.2s ease;
}

.table-card tbody tr:hover {
    background: #f4f7ff;
    transform: scale(1.002);
}

/* ===============================
   CONTACT BUTTON STYLE
================================ */

.table-card .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.table-card .btn-success {
    background: #1cc88a;
    border: none;
}

.table-card .btn-success:hover {
    background: #17a673;
}

.table-card .btn-primary {
    background: #4e73df;
    border: none;
}

.table-card .btn-primary:hover {
    background: #2e59d9;
}

/* ===============================
   RESPONSIVE IMPROVEMENT
================================ */

@media (max-width: 768px) {
    .table-card thead {
        display: none;
    }

    .table-card table,
    .table-card tbody,
    .table-card tr,
    .table-card td {
        display: block;
        width: 100%;
    }

    .table-card tr {
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .table-card td {
       
        text-align: right;
        position: relative;
    }

    .table-card td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        font-weight: 600;
        text-align: left;
        color: #666;
    }
}


.male-all-employee{
    display: flex;
    height: 550px;
}

.table-card{
    margin-left: 50px;
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
    <!---employee male - female--->
    <div class="male-all-employee">
    <div class="employee-card">
        
        <!-- Header -->
        <div class="card-header">
            <div class="icon-badge">👥</div>
            <h2>Employee Structure</h2>
        </div>

        <!-- Total row (highlighted) -->
        <div class="info-row total-row">
            <span class="label">📋 Total</span>
            <span class="value">{{ $stats['totalHiredEmployees'] ?? 0 }}</span>
        </div>

        <!-- Male -->
        <div class="info-row">
            <span class="label"><span>♂️</span> Male</span>
            <span class="value">{{ $stats['malePercentage'] ?? 0 }}% <small>({{ $stats['maleEmployees'] ?? 0 }})</small></span>
        </div>

        <!-- Female -->
        <div class="info-row">
            <span class="label"><span>♀️</span> Female</span>
            <span class="value">{{ $stats['femalePercentage'] ?? 0 }}% <small>({{ $stats['femaleEmployees'] ?? 0 }})</small></span>
        </div>

        <!-- Visual progress bars (extra, but keeps UI rich) -->
        <div class="progress-section">
            <div class="progress-item">
                <div class="progress-header">
                    <span>👨 Male</span>
                    <span>{{ $stats['malePercentage'] ?? 0 }}%</span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-fill-male" style="width: {{ $stats['malePercentage'] ?? 0 }}%;"></div>
                </div>
            </div>
            <div class="progress-item">
                <div class="progress-header">
                    <span>👩 Female</span>
                    <span>{{ $stats['femalePercentage'] ?? 0 }}%</span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-fill-female" style="width: {{ $stats['femalePercentage'] ?? 0 }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Summary footer -->
        <div class="footer-note">
            👤 <strong>{{ $stats['maleEmployees'] ?? 0 }} Male</strong> · <strong>{{ $stats['femaleEmployees'] ?? 0 }} Female</strong> · Total {{ $stats['totalHiredEmployees'] ?? 0 }}
        </div>

    </div>
    
    <!-- 🔹 All Employees Table -->
    @if(isset($allEmployees) && $allEmployees->count() > 0)

<style>
/* Scroll Wrapper */
.employee-table-wrapper {
    max-height: 400px; /* Height control yaha se */
    overflow-y: auto;
}

/* Sticky Header */
.employee-table-wrapper thead th {
    position: sticky;
    top: 0;
    background: #ffffff;
    z-index: 2;
    box-shadow: 0 2px 2px rgba(0,0,0,0.05);
}

/* Name wrap fix */
.table td {
    white-space: nowrap;
}

/* Search box styling */
.employee-search {
    max-width: 250px;
}

/* Smooth scrollbar */
.employee-table-wrapper::-webkit-scrollbar {
    width: 6px;
}

.employee-table-wrapper::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}
</style>


<div class="card table-card mb-4 shadow-sm">
    
    <!-- Header -->
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        
        <h5 class="mb-0 fw-semibold">
            👥 All Employees ({{ $allEmployees->count() }})
        </h5>

        <!-- Search -->
        <input type="text"
               id="employeeSearch"
               class="form-control form-control-sm employee-search"
               placeholder="🔍 Search employee...">
    </div>


    <!-- Table -->
    <div class="card-body p-0">
        <div class="table-responsive employee-table-wrapper">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th class="text-center">Contact</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    
                    @foreach($allEmployees as $employee)
                    <tr>
                        <td>
                            <strong>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </strong>
                        </td>

                        <td>
                            {{ $employee->department ?? 'N/A' }}
                        </td>

                        <td class="text-center">
                            
                            @if($employee->phone)
                            <a href="tel:{{ $employee->phone }}"
                               class="btn btn-sm btn-success me-1"
                               title="Call">
                                <i class="bi bi-telephone-fill"></i>
                            </a>
                            @endif

                            @if($employee->email)
                            <a href="mailto:{{ $employee->email }}"
                               class="btn btn-sm btn-primary me-1"
                               title="Email">
                                <i class="bi bi-envelope-fill"></i>
                            </a>
                            @endif

                            @if($employee->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $employee->phone) }}"
                               target="_blank"
                               class="btn btn-sm btn-success"
                               title="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            @endif

                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Live Search Script -->
<script>
document.getElementById("employeeSearch").addEventListener("keyup", function () {

    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#employeeTableBody tr");

    rows.forEach(function (row) {

        let name = row.cells[0].innerText.toLowerCase();
        let department = row.cells[1].innerText.toLowerCase();

        if (name.includes(value) || department.includes(value)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });

});
</script>

@endif

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
