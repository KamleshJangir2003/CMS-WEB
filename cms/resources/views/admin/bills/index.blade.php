@extends('auth.layouts.app')

@section('title', 'Bill Management')
<style>
  .main-content {
   padding-left: 130px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
    margin-top: 80px;
}

/* Mobile Bills Page Responsive */
@media (max-width: 768px) {
    .main-content {
        padding-left: 15px !important;
        margin-top: 70px;
    }
    
    .page-title {
        font-size: 18px;
    }
    
    .card-header h5 {
        font-size: 16px;
    }
    
    .form-label {
        font-size: 12px;
    }
    
    .form-control {
        font-size: 13px;
        padding: 6px 8px;
    }
    
    .table th,
    .table td {
        font-size: 10px;
        padding: 4px 2px;
    }
    
    .badge {
        font-size: 9px;
    }
    
    .btn-sm {
        font-size: 9px;
        padding: 3px 5px;
    }
    
    .btn-sm i {
        font-size: 8px;
    }
}

</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Bill Management</h4>
            </div>
        </div>
    </div>

    <!-- Add New Bill -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add New Bill</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.bills.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Bill Type</label>
                                    <input type="text" name="bill_type" class="form-control" placeholder="e.g. Electricity, Internet" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Issue Date</label>
                                    <input type="date" name="issue_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" name="due_date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add Bill
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bills List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Bills</h5>
                </div>
                <div class="card-body">
                    @if(isset($bills) && $bills->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Bill Type</th>
                                        <th>Amount</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bills as $index => $bill)
                                        <tr id="bill-row-{{ $bill->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $bill->bill_type }}</strong></td>
                                            <td>₹{{ number_format($bill->amount, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($bill->issue_date)->format('d M Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}</td>
                                            <td>
                                                @if($bill->status == 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif(\Carbon\Carbon::parse($bill->due_date)->isPast())
                                                    <span class="badge bg-danger">Overdue</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($bill->status != 'paid')
                                                    <button type="button" class="btn btn-sm btn-success mark-paid-btn" data-id="{{ $bill->id }}">
                                                        <i class="fas fa-check"></i> Mark Paid
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-1" onclick="deleteBill({{ $bill->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No bills found. Add your first bill above!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark as paid functionality
    document.querySelectorAll('.mark-paid-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const billId = this.dataset.id;
            markBillAsPaid(billId);
        });
    });
});

function markBillAsPaid(billId) {
    if (!confirm('Are you sure you want to mark this bill as paid?')) {
        return;
    }
    
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
            location.reload();
        } else {
            alert('Error marking bill as paid');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error marking bill as paid');
    });
}

function deleteBill(billId) {
    if (!confirm('Are you sure you want to delete this bill?')) {
        return;
    }
    
    fetch(`/admin/bills/${billId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`bill-row-${billId}`).remove();
        } else {
            alert('Error deleting bill');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting bill');
    });
}
</script>
@endsection