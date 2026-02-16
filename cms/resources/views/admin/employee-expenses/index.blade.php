@extends('auth.layouts.app')
<style>
    .main-content{
        padding-left: 130px;
        padding-top: 80px;
    }
    
    /* Mobile Employee Expenses Page Responsive */
    @media (max-width: 768px) {
        .main-content {
            padding-left: 15px !important;
            padding-top: 70px;
        }
        
        .stats-card .card-body {
            padding: 10px;
        }
        
        .stats-card h3 {
            font-size: 16px;
        }
        
        .stats-card h6 {
            font-size: 10px;
        }
        
        .stats-card i {
            font-size: 18px !important;
        }
        
        .btn-group .btn {
            font-size: 9px;
            padding: 3px 6px;
        }
        
        .table th,
        .table td {
            font-size: 9px;
            padding: 3px 2px;
        }
        
        .badge {
            font-size: 7px;
        }
        
        .btn-sm {
            font-size: 8px;
            padding: 2px 3px;
        }
        
        .modal-body {
            font-size: 11px;
        }
    }
</style>
@section('title', 'Employee Expenses')
@section('page-title', 'Employee Expense Management')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <h4>Employee Expense Management</h4>
            <p class="text-muted">Review and approve employee expense submissions</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Pending</h6>
                            <h3 class="text-warning">{{ $stats['pending'] }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Approved</h6>
                            <h3 class="text-success">{{ $stats['approved'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Rejected</h6>
                            <h3 class="text-danger">{{ $stats['rejected'] }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Approved</h6>
                            <h3 class="text-primary">₹{{ number_format($stats['total_amount'], 2) }}</h3>
                        </div>
                        <i class="fas fa-rupee-sign fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Employee Expenses</h5>
                <div>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.employee-expenses.index', ['status' => 'all']) }}" 
                           class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                        <a href="{{ route('admin.employee-expenses.index', ['status' => 'pending']) }}" 
                           class="btn btn-sm {{ $status === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Pending</a>
                        <a href="{{ route('admin.employee-expenses.index', ['status' => 'approved']) }}" 
                           class="btn btn-sm {{ $status === 'approved' ? 'btn-success' : 'btn-outline-success' }}">Approved</a>
                        <a href="{{ route('admin.employee-expenses.index', ['status' => 'rejected']) }}" 
                           class="btn btn-sm {{ $status === 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">Rejected</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($expenses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>
                                        <strong>{{ $expense->employee->first_name }} {{ $expense->employee->last_name }}</strong><br>
                                        <small class="text-muted">{{ $expense->employee->email }}</small>
                                    </td>
                                    <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                    <td>{{ Str::limit($expense->description, 50) }}</td>
                                    <td><span class="badge bg-secondary">{{ $expense->category }}</span></td>
                                    <td>₹{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->payment_method }}</td>
                                    <td>
                                        @if($expense->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($expense->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#expenseModal{{ $expense->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($expense->status === 'pending')
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $expense->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $expense->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Expense Details Modal -->
                                <div class="modal fade" id="expenseModal{{ $expense->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Expense Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>Employee:</strong> {{ $expense->employee->first_name }} {{ $expense->employee->last_name }}<br>
                                                        <strong>Date:</strong> {{ $expense->expense_date->format('d M Y') }}<br>
                                                        <strong>Amount:</strong> ₹{{ number_format($expense->amount, 2) }}<br>
                                                        <strong>Category:</strong> {{ $expense->category }}<br>
                                                        <strong>Payment Method:</strong> {{ $expense->payment_method }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Status:</strong> 
                                                        <span class="badge 
                                                            @if($expense->status === 'pending') bg-warning
                                                            @elseif($expense->status === 'approved') bg-success
                                                            @else bg-danger @endif">
                                                            {{ ucfirst($expense->status) }}
                                                        </span><br>
                                                        <strong>Submitted:</strong> {{ $expense->created_at->format('d M Y, h:i A') }}
                                                    </div>
                                                </div>
                                                <hr>
                                                <strong>Description:</strong>
                                                <p>{{ $expense->description }}</p>
                                                
                                                @if($expense->receipt_path)
                                                    <strong>Receipt:</strong>
                                                    <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-file-alt me-2"></i>View Receipt
                                                    </a>
                                                @endif

                                                @if($expense->admin_notes)
                                                    <hr>
                                                    <strong>Admin Notes:</strong>
                                                    <div class="alert alert-info">{{ $expense->admin_notes }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($expense->status === 'pending')
                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $expense->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.employee-expenses.update-status', $expense) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Approve Expense</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to approve this expense of <strong>₹{{ number_format($expense->amount, 2) }}</strong>?</p>
                                                        <div class="mb-3">
                                                            <label for="admin_notes" class="form-label">Notes (Optional)</label>
                                                            <textarea class="form-control" name="admin_notes" rows="3" placeholder="Add any notes..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Approve</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $expense->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.employee-expenses.update-status', $expense) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Expense</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to reject this expense of <strong>₹{{ number_format($expense->amount, 2) }}</strong>?</p>
                                                        <div class="mb-3">
                                                            <label for="admin_notes" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" name="admin_notes" rows="3" placeholder="Please provide reason for rejection..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No employee expenses found</h5>
                    <p class="text-muted">Employee expense submissions will appear here</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection