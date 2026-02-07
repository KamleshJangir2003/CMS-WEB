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
</style>

<div class="dashboard-wrapper">

    <!-- 🔹 Stats Cards -->
    <div class="row g-4 mb-4">
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

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success">
                <div class="card-body">
                    <div class="stat-title">Total Admins</div>
                    <div class="stat-number">{{ $stats['totalAdmins'] ?? 0 }}</div>
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-info">
                <div class="card-body">
                    <div class="stat-title">Total Clients</div>
                    <div class="stat-number">{{ $stats['totalClients'] ?? 0 }}</div>
                    <i class="bi bi-briefcase-fill"></i>
                </div>
            </div>
        </div>
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
                                <form action="{{ route('admin.approve', $user->id) }}" method="POST">
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
    <div class="card welcome-card">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="mb-1">Welcome, {{ Auth::user()->first_name }} 👋</h4>
                <p class="mb-0">You are logged in as <strong>Administrator</strong></p>
            </div>
            <a href="{{ route('admin.users') }}" class="btn btn-light mt-3 mt-md-0">
                <i class="bi bi-gear"></i> Manage Users
            </a>
        </div>
    </div>

</div>
@endsection
