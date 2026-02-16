@extends('auth.layouts.app')

<style>
    .main-content{
        padding-left: 130px;
        padding-top: 80px;
    }
    
    /* Mobile Expenses Page Responsive */
    @media (max-width: 768px) {
        .main-content {
            padding-left: 15px !important;
            padding-top: 70px;
        }
        
        .stats-card .card-body {
            padding: 12px;
        }
        
        .stats-card h3 {
            font-size: 18px;
        }
        
        .stats-card h6 {
            font-size: 11px;
        }
        
        .stats-card i {
            font-size: 20px !important;
        }
        
        .table th,
        .table td {
            font-size: 10px;
            padding: 4px 2px;
        }
        
        .badge {
            font-size: 8px;
        }
        
        .btn-sm {
            font-size: 9px;
            padding: 2px 4px;
        }
        
        .form-control,
        .form-select {
            font-size: 12px;
            padding: 6px 8px;
        }
        
        .form-label {
            font-size: 11px;
        }
    }
</style>
@section('title', 'Admin Expenses')
@section('page-title', 'Expense Management')

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
                <!-- <h4>Admin Expense Management</h4>
                <p class="text-muted">Manage office and business expenses</p> -->
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                <i class="fas fa-plus me-2"></i>Add Expense
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Monthly Total</h6>
                            <h3 class="text-primary">₹{{ number_format($monthly_total, 2) }}</h3>
                        </div>
                        <i class="fas fa-calendar-month fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Yearly Total</h6>
                            <h3 class="text-success">₹{{ number_format($yearly_total, 2) }}</h3>
                        </div>
                        <i class="fas fa-calendar-year fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Expenses</h6>
                            <h3 class="text-info">{{ $expenses->count() }}</h3>
                        </div>
                        <i class="fas fa-receipt fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Categories</h6>
                            <h3 class="text-warning">{{ $categories->count() }}</h3>
                        </div>
                        <i class="fas fa-tags fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Month</label>
                    <input type="month" class="form-control" name="month" value="{{ $selected_month }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ $category_filter === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" name="payment_method">
                        <option value="">All Methods</option>
                        <option value="UPI" {{ $payment_filter === 'UPI' ? 'selected' : '' }}>UPI</option>
                        <option value="Bank Transfer" {{ $payment_filter === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="Cash" {{ $payment_filter === 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Card" {{ $payment_filter === 'Card' ? 'selected' : '' }}>Card</option>
                        <option value="Scanner" {{ $payment_filter === 'Scanner' ? 'selected' : '' }}>Scanner</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Expense Records</h5>
        </div>
        <div class="card-body">
            @if($expenses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Reference</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>{{ date('d M Y', strtotime($expense->expense_date)) }}</td>
                                    <td>{{ Str::limit($expense->description, 50) }}</td>
                                    <td><span class="badge bg-secondary">{{ $expense->category }}</span></td>
                                    <td>₹{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->payment_method }}</td>
                                    <td>{{ $expense->reference_number ?? '-' }}</td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="delete" value="{{ $expense->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No expenses found</h5>
                    <p class="text-muted">Add your first expense to get started</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                @csrf
                <input type="hidden" name="add_expense" value="1">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="expense_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select class="form-select" name="payment_method" required>
                            <option value="">Select Method</option>
                            <option value="UPI">UPI</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                            <option value="Card">Card</option>
                            <option value="Scanner">Scanner</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-control" name="reference_number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection