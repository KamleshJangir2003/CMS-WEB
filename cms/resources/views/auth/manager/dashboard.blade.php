@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h1>👨‍💼 Manager Dashboard</h1>
        <p>Welcome, {{ Auth::user()->first_name }}!</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['totalInterviews'] }}</div>
            <div class="stat-label">Total Interviews</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['pendingInterviews'] }}</div>
            <div class="stat-label">Pending Interviews</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['completedInterviews'] }}</div>
            <div class="stat-label">Completed</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['selectedCandidates'] }}</div>
            <div class="stat-label">Selected</div>
        </div>
    </div>

    <div class="content-card">
        <h3>🗓️ Upcoming Interviews</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Role</th>
                        <th>Round</th>
                        <th>Date & Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingInterviews as $interview)
                        <tr>
                            <td>{{ $interview->candidate_name }}</td>
                            <td>{{ $interview->job_role }}</td>
                            <td><span class="badge badge-info">{{ $interview->interview_round }}</span></td>
                            <td>
                                {{ $interview->interview_date->format('M d, Y') }}<br>
                                <small>{{ date('g:i A', strtotime($interview->start_time)) }}</small>
                            </td>
                            <td>
                                <a href="{{ route('manager.interviews.index') }}" class="btn btn-sm btn-primary">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No upcoming interviews</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #4f46e5;
}

.stat-label {
    color: #666;
    margin-top: 5px;
}

.content-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.badge-info { background-color: #17a2b8; color: white; }
</style>
@endsection