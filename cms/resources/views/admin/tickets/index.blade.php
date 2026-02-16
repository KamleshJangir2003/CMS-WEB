@extends('auth.layouts.app')

@section('title', 'Employee Tickets Management')

<style>
.main-content {
    padding-left: 130px;
    min-height: 100vh;
    transition: margin-left 0.3s ease;
    margin-top: 80px;
}

/* Mobile Tickets Page Responsive */
@media (max-width: 768px) {
    .main-content {
        padding-left: 15px !important;
        margin-top: 70px;
    }
    
    .card-title {
        font-size: 14px;
    }
    
    .card-body h3 {
        font-size: 20px;
    }
    
    .card-body h5 {
        font-size: 12px;
    }
    
    .table th,
    .table td {
        font-size: 9px;
        padding: 3px 2px;
    }
    
    .badge {
        font-size: 7px;
        padding: 2px 4px;
    }
    
    .btn-sm {
        font-size: 8px;
        padding: 2px 4px;
    }
    
    .btn-sm i {
        font-size: 7px;
    }
    
    .modal-body {
        font-size: 12px;
    }
    
    .form-control,
    .form-select {
        font-size: 11px;
        padding: 5px 6px;
    }
}
</style>

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Tickets</h5>
                    <h3 class="text-primary">{{ $tickets->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <h5 class="card-title text-warning">Open Tickets</h5>
                    <h3 class="text-warning">{{ $tickets->where('status', 'open')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <h5 class="card-title text-info">In Progress</h5>
                    <h3 class="text-info">{{ $tickets->where('status', 'in_progress')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <h5 class="card-title text-success">Resolved</h5>
                    <h3 class="text-success">{{ $tickets->where('status', 'resolved')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Employee Support Tickets</h4>
        </div>
        <div class="card-body">
            @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                            <tr class="{{ !$ticket->viewed_at ? 'table-warning' : '' }}">
                                <td>#{{ $ticket->id }}</td>
                                <td>
                                    <strong>{{ $ticket->employee->full_name ?? $ticket->employee->first_name . ' ' . $ticket->employee->last_name }}</strong>
                                    <br><small class="text-muted">{{ $ticket->employee->department }}</small>
                                </td>
                                <td>{{ $ticket->title }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($ticket->type) }}</span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($ticket->priority == 'urgent') bg-danger
                                        @elseif($ticket->priority == 'high') bg-warning
                                        @elseif($ticket->priority == 'medium') bg-info
                                        @else bg-success
                                        @endif">
                                        {{ ucfirst($ticket->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($ticket->status == 'open') bg-primary
                                        @elseif($ticket->status == 'in_progress') bg-warning
                                        @elseif($ticket->status == 'resolved') bg-success
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                    @if(!$ticket->viewed_at)
                                        <small class="text-danger d-block">
                                            <i class="fas fa-exclamation-circle"></i> New
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ticketModal{{ $ticket->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    @if(!$ticket->viewed_at)
                                        <form method="POST" action="{{ route('admin.tickets.mark-viewed', $ticket->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-check"></i> Mark Viewed
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No tickets found</h5>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Ticket Detail Modals -->
@foreach($tickets as $ticket)
<div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ticket #{{ $ticket->id }} - {{ $ticket->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Employee:</strong> {{ $ticket->employee->full_name ?? $ticket->employee->first_name . ' ' . $ticket->employee->last_name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Department:</strong> {{ $ticket->employee->department }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Type:</strong> {{ ucfirst($ticket->type) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Priority:</strong> 
                        <span class="badge 
                            @if($ticket->priority == 'urgent') bg-danger
                            @elseif($ticket->priority == 'high') bg-warning
                            @elseif($ticket->priority == 'medium') bg-info
                            @else bg-success
                            @endif">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <strong>Created:</strong> {{ $ticket->created_at->format('M d, Y g:i A') }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Description:</strong>
                    <div class="border p-3 mt-2 bg-light">
                        {{ $ticket->description }}
                    </div>
                </div>

                <!-- Admin Response Form -->
                <form method="POST" action="{{ route('admin.tickets.respond', $ticket->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"><strong>Admin Response:</strong></label>
                        <textarea name="admin_response" class="form-control" rows="4" placeholder="Type your response here...">{{ $ticket->admin_response }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Status:</label>
                            <select name="status" class="form-select">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-reply me-2"></i>Update Response
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection