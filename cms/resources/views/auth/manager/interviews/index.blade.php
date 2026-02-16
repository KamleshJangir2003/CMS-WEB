@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h1>📅 My Interviews</h1>
        <p>Interviews assigned to you</p>
    </div>

    <div class="content-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Job Role</th>
                        <th>Round</th>
                        <th>Date & Time</th>
                        <th>Mode</th>
                        <th>Status</th>
                        <th>Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interviews as $interview)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $interview->candidate_name }}</strong><br>
                                    <small class="text-muted">{{ $interview->candidate_email }}</small>
                                </div>
                            </td>
                            <td>{{ $interview->job_role }}</td>
                            <td><span class="badge badge-info">{{ $interview->interview_round }}</span></td>
                            <td>
                                <div>
                                    <strong>{{ $interview->interview_date->format('M d, Y') }}</strong><br>
                                    <small>{{ date('g:i A', strtotime($interview->start_time)) }} - {{ date('g:i A', strtotime($interview->end_time)) }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $interview->interview_mode == 'Online' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $interview->interview_mode }}
                                </span>
                                @if($interview->meeting_link)
                                    <br><a href="{{ $interview->meeting_link }}" target="_blank" class="meeting-link">Join Meeting</a>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $interview->status == 'Scheduled' ? 'primary' : 'success' }}">
                                    {{ $interview->status }}
                                </span>
                            </td>
                            <td>
                                @if($interview->result == 'Pending')
                                    <span class="badge badge-secondary">Pending</span>
                                @elseif($interview->result == 'Selected')
                                    <span class="badge badge-success">Selected</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if($interview->status == 'Scheduled')
                                        <button class="btn btn-sm btn-success" onclick="markComplete({{ $interview->id }})">
                                            Mark Complete
                                        </button>
                                    @endif
                                    @if($interview->status == 'Completed' && $interview->result == 'Pending')
                                        <button class="btn btn-sm btn-success" onclick="updateResult({{ $interview->id }}, 'Selected')">
                                            ✓ Select
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="showRejectModal({{ $interview->id }})">
                                            ✗ Reject
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No interviews assigned to you yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $interviews->links() }}
    </div>
</div>

<!-- Reject Modal -->
<div class="modal" id="rejectModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Candidate</h5>
                <button type="button" class="close" onclick="closeModal('rejectModal')">&times;</button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Please provide reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('rejectModal')">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Candidate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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
.badge-success { background-color: #28a745; color: white; }
.badge-warning { background-color: #ffc107; color: black; }
.badge-primary { background-color: #2eacb3; color: white; }
.badge-danger { background-color: #dc3545; color: white; }
.badge-secondary { background-color: #6c757d; color: white; }

.action-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.meeting-link {
    color: #2eacb3;
    text-decoration: none;
    font-size: 12px;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal.show { display: block; }

.modal-dialog {
    position: relative;
    width: auto;
    max-width: 500px;
    margin: 50px auto;
}

.modal-content {
    background: white;
    border-radius: 8px;
    padding: 0;
}

.modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-body { padding: 20px; }

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.form-group { margin-bottom: 15px; }

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary { background: #6c757d; color: white; }
.btn-danger { background: #dc3545; color: white; }
.btn-success { background: #28a745; color: white; }

.close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
}
</style>

<script>
let currentInterviewId = null;

function markComplete(interviewId) {
    if (confirm('Mark this interview as completed?')) {
        fetch(`/manager/interviews/${interviewId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function updateResult(interviewId, result) {
    if (confirm(`Mark candidate as ${result.toLowerCase()}?`)) {
        fetch(`/manager/interviews/${interviewId}/result`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ result: result })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function showRejectModal(interviewId) {
    currentInterviewId = interviewId;
    document.getElementById('rejectModal').classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch(`/manager/interviews/${currentInterviewId}/result`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            result: 'Rejected',
            rejection_reason: formData.get('rejection_reason')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('rejectModal');
            location.reload();
        }
    });
});
</script>
@endsection