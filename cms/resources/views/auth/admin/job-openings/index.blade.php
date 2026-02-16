@extends('auth.layouts.app')

@section('content')
<style>
.dashboard-wrapper {
    padding: 25px;
    margin-left: 130px;
    margin-top: 60px;
}

/* Mobile Job Openings Page Responsive */
@media (max-width: 768px) {
    .dashboard-wrapper {
        margin-left: 0 !important;
        margin-top: 70px;
        padding: 15px;
    }
    
    .job-card {
        margin-bottom: 15px;
    }
    
    .job-card .card-body {
        padding: 12px;
    }
    
    .card-title {
        font-size: 16px;
    }
    
    .status-badge {
        font-size: 10px;
        padding: 4px 8px;
    }
    
    .badge {
        font-size: 9px;
    }
    
    .btn-sm {
        font-size: 10px;
        padding: 3px 5px;
    }
    
    .btn-sm i {
        font-size: 9px;
    }
    
    .text-muted {
        font-size: 11px;
    }
}
.job-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.job-card:hover {
    transform: translateY(-2px);
}
.status-badge {
    font-size: 12px;
    padding: 6px 12px;
}
</style>

<div class="dashboard-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-briefcase me-2"></i>Job Opening Management</h2>
        <a href="{{ route('admin.job-openings.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Create Job Opening
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse($jobOpenings as $job)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card job-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">{{ $job->job_title }}</h5>
                            <span class="badge status-badge {{ $job->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">Shift:</small>
                            <span class="badge bg-info">{{ $job->shift }}</span>
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">Salary:</small>
                            <strong>₹{{ number_format($job->salary, 2) }}</strong>
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">Timing:</small>
                            <span>{{ $job->job_timing }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Est. Time to Hire:</small>
                            <span>{{ $job->estimated_time_to_hire }} days</span>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.job-openings.show', $job) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.job-openings.edit', $job) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            
                            @if($job->status === 'active')
                                <form action="{{ route('admin.job-openings.close', $job) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                            onclick="return confirm('Mark this job as hired/closed?')">
                                        <i class="fa-solid fa-check"></i> Close
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.job-openings.activate', $job) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-info">
                                        <i class="fa-solid fa-redo"></i> Reopen
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.job-openings.destroy', $job) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to delete this job opening?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fa-solid fa-briefcase fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Job Openings Found</h4>
                    <p class="text-muted">Create your first job opening to get started.</p>
                    <a href="{{ route('admin.job-openings.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus me-2"></i>Create Job Opening
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection