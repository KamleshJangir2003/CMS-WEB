@extends('auth.layouts.app')

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<style>
    .container-fluid{
    margin-top: 60px !important;
    padding-left: 130px !important;
}
.status-badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 500;
}
.status-not-started { background: #f8f9fa; color: #6c757d; }
.status-pending { background: #fff3cd; color: #856404; }
.status-completed { background: #d1edff; color: #0c63e4; }
.status-in-progress { background: #e2e3e5; color: #495057; }
.document-stats {
    font-size: 12px;
    color: #6c757d;
}
.table th {
    font-size: 13px;
    white-space: nowrap;
}
.table td {
    font-size: 12px;
    vertical-align: middle;
}
.fw-medium {
    font-weight: 500;
}
.text-success {
    color: #28a745 !important;
}
.text-primary {
    color: #007bff !important;
}
.text-info {
    color: #17a2b8 !important;
}
.form-select-sm {
    font-size: 12px;
    padding: 4px 8px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #fff;
}
.form-select-sm:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 fw-semibold">Employee Documents Status</h4>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Joining Date</th>
                        <th>CTC</th>
                        <th>In Hand</th>
                        <th class="text-center">Hired Status</th>
                        <th class="text-center">Document Status</th>
                        <th class="text-center">Progress</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://i.pravatar.cc/40?u={{ $emp->id }}"
                                     class="rounded-circle me-2"
                                     width="40" height="40">
                                <div>
                                    <div class="fw-medium">{{ $emp->full_name }}</div>
                                    <small class="text-muted">{{ $emp->phone ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $emp->email }}</td>
                        <td>{{ ucfirst($emp->department ?? 'N/A') }}</td>
                        <td>
                            @if($emp->joining_date)
                                <span class="text-success fw-medium">{{ $emp->joining_date->format('M d, Y') }}</span>
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </td>
                        <td>
                            @if($emp->current_ctc)
                                <span class="text-primary fw-medium">₹{{ number_format($emp->current_ctc) }}</span>
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </td>
                        <td>
                            @if($emp->in_hand_salary)
                                <span class="text-info fw-medium">₹{{ number_format($emp->in_hand_salary) }}</span>
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.employees.update-hired-status', $emp->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="hired_status" class="form-select form-select-sm" style="width: 120px;" onchange="this.form.submit()">
                                    <option value="not_hired" {{ ($emp->hired_status ?? 'not_hired') == 'not_hired' ? 'selected' : '' }}>Not Hired</option>
                                    <option value="hired" {{ ($emp->hired_status ?? 'not_hired') == 'hired' ? 'selected' : '' }}>Hired</option>
                                </select>
                            </form>
                        </td>
                        <td class="text-center">
                            @php
                                $stats = $emp->document_stats;
                                $statusClass = 'status-' . $stats['status'];
                                $statusText = match($stats['status']) {
                                    'not_started' => 'Not Started',
                                    'pending' => 'Pending Review',
                                    'completed' => 'All Verified',
                                    'in_progress' => 'In Progress',
                                    default => 'Unknown'
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            <div class="document-stats mt-1">
                                @if($stats['verified'] > 0)
                                    <span class="text-success">✓ {{ $stats['verified'] }} Verified</span>
                                @endif
                                @if($stats['pending'] > 0)
                                    <span class="text-warning">⏳ {{ $stats['pending'] }} Pending</span>
                                @endif
                                @if($stats['missing'] > 0)
                                    <span class="text-danger">❌ {{ $stats['missing'] }} Missing</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="progress" style="height: 8px; width: 80px; margin: 0 auto;">
                                @php
                                    $percentage = ($stats['uploaded'] / $stats['total_required']) * 100;
                                    $progressClass = $percentage == 100 ? 'bg-success' : ($percentage > 50 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="progress-bar {{ $progressClass }}" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                            <small class="text-muted">{{ $stats['uploaded'] }}/{{ $stats['total_required'] }}</small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.employees.document', ['userId' => $emp->id]) }}"
                               class="btn btn-sm btn-primary"
                               title="View Documents">
                                <i class="fa-solid fa-file-lines me-1"></i> Documents
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            No employees found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <small class="text-muted">Showing {{ $employees->count() }} employees</small>
        </div>
    </div>
</div>
@endsection