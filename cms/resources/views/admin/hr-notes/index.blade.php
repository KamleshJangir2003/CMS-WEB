@extends('auth.layouts.app')

@section('title', 'HR Notes - Daily Tasks')
<style>
    /* ===== SIDEBAR FIX WIDTH ===== */


/* ===== CONTENT KO SIDEBAR SE BAHAR RAKHNE KE LIYE ===== */
.main-content {
   padding-left: 130px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
    margin-top: 80px;
}

/* Mobile HR Notes Page Responsive */
@media (max-width: 768px) {
    .main-content {
        padding-left: 15px !important;
        margin-top: 70px;
    }
    
    .page-content,
    .container-fluid {
        padding: 10px;
    }
    
    .card-body {
        padding: 12px;
    }
    
    .form-control,
    .form-select {
        font-size: 12px;
        padding: 6px 8px;
    }
    
    .form-label {
        font-size: 11px;
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
    
    .breadcrumb {
        font-size: 10px;
    }
}

/* ===== PAGE CONTENT PROPER SPACING ===== */
.page-content,
.container-fluid {
    padding: 20px;
}

/* ===== AGAR SIDEBAR COLLAPSE HO ===== */
body.sidebar-collapsed .main-content {
    margin-left: 70px;
}

</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">HR Notes - Daily Tasks</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">HR Notes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Add New Task -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add New Task</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.hr-notes.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="title" class="form-label">Task Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="1"></textarea>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Tasks -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">Today's Tasks ({{ date('d M Y') }})</h5>
                </div>
                <div class="card-body">
                    @if($todayNotes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">SN</th>
                                        <th width="30%">Task</th>
                                        <th width="35%">Description</th>
                                        <th width="15%">Status</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todayNotes as $index => $note)
                                        <tr id="task-row-{{ $note->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $note->title }}</strong>
                                            </td>
                                            <td>{{ $note->description ?? 'No description' }}</td>
                                            <td>
                                                <select class="form-select form-select-sm status-select" data-id="{{ $note->id }}">
                                                    <option value="pending" {{ $note->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="completed" {{ $note->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="cancelled" {{ $note->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.hr-notes.destroy', $note->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
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
                        <div class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No tasks for today. Add your first task above!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- All Tasks History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Tasks History</h5>
                </div>
                <div class="card-body">
                    @if($allNotes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Task</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allNotes as $note)
                                        <tr>
                                            <td>{{ $note->date->format('d M Y') }}</td>
                                            <td><strong>{{ $note->title }}</strong></td>
                                            <td>{{ $note->description ?? 'No description' }}</td>
                                            <td id="status-badge-{{ $note->id }}">
                                                @if($note->status == 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($note->status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $note->creator->first_name ?? 'Unknown' }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.hr-notes.destroy', $note->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
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
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $allNotes->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No task history found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const noteId = this.dataset.id;
            const status = this.value;
            const selectElement = this;
            
            fetch(`/admin/hr-notes/${noteId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Visual feedback for dropdown
                    selectElement.style.backgroundColor = '#d4edda';
                    setTimeout(() => {
                        selectElement.style.backgroundColor = '';
                    }, 1000);
                    
                    // Update status badge in history table
                    updateStatusBadge(noteId, status);
                    
                    // Hide task from Today's Tasks if completed or cancelled
                    if (status === 'completed' || status === 'cancelled') {
                        const taskRow = document.getElementById(`task-row-${noteId}`);
                        if (taskRow) {
                            taskRow.style.transition = 'opacity 0.5s';
                            taskRow.style.opacity = '0';
                            setTimeout(() => {
                                taskRow.remove();
                                // Check if no tasks left
                                const tbody = taskRow.closest('tbody');
                                if (tbody && tbody.children.length === 0) {
                                    const tableContainer = tbody.closest('.table-responsive');
                                    if (tableContainer) {
                                        tableContainer.innerHTML = `
                                            <div class="text-center py-4">
                                                <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No pending tasks for today!</p>
                                            </div>
                                        `;
                                    }
                                }
                            }, 500);
                        }
                    }
                    
                    // Show toast notification
                    showToast('Status updated successfully!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error updating status', 'error');
            });
        });
    });
});

function updateStatusBadge(noteId, status) {
    const badgeElement = document.getElementById(`status-badge-${noteId}`);
    if (badgeElement) {
        let badgeClass, badgeText;
        
        switch(status) {
            case 'completed':
                badgeClass = 'bg-success';
                badgeText = 'Completed';
                break;
            case 'cancelled':
                badgeClass = 'bg-danger';
                badgeText = 'Cancelled';
                break;
            default:
                badgeClass = 'bg-warning';
                badgeText = 'Pending';
        }
        
        badgeElement.innerHTML = `<span class="badge ${badgeClass}">${badgeText}</span>`;
    }
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `${message} <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentElement) toast.remove();
    }, 3000);
}
</script>
@endsection