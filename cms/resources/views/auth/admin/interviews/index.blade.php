@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h1>📅 Interview Management</h1>
        <div class="header-actions">
            <a href="{{ route('admin.interviews.selected') }}" class="btn btn-success">
                <i class="fas fa-users"></i> Selected Employees
            </a>
            <a href="{{ route('admin.interviews.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Schedule New Interview
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="content-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Job Role</th>
                        <th>Interview Round</th>
                        <th>Date & Time</th>
                        <th>Interviewer</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interviews as $interview)
                        <tr id="interview-row-{{ $interview->id }}">
                            <td>
                                <strong>{{ $interview->candidate_name }}</strong>
                            </td>
                            <td>{{ $interview->job_role }}</td>
                            <td>
                                <span class="badge badge-info">{{ $interview->interview_round }}</span>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $interview->interview_date->format('M d, Y') }}</strong><br>
                                    <small>{{ date('g:i A', strtotime($interview->start_time)) }} - {{ date('g:i A', strtotime($interview->end_time)) }}</small>
                                </div>
                            </td>
                            <td>{{ $interview->interviewer }}</td>
                            <td id="status-{{ $interview->id }}">
                                @if($interview->status == 'Completed' && $interview->result == 'Pending')
                                    <span class="badge badge-warning">⏳ Pending Decision</span>
                                @elseif($interview->result == 'Selected')
                                    <span class="badge badge-success">✅ Selected</span>
                                @elseif($interview->result == 'Rejected')
                                    <span class="badge badge-danger">❌ Rejected</span>
                                @else
                                    <span class="badge badge-primary">{{ $interview->status }}</span>
                                @endif
                            </td>
                            <td id="actions-{{ $interview->id }}">
                                @if($interview->status == 'Scheduled')
                                    <button class="btn btn-sm btn-primary complete-btn" onclick="markCompleted({{ $interview->id }})">
                                        <i class="fas fa-check-circle"></i> Complete
                                    </button>
                                @elseif($interview->status == 'Completed' && $interview->result == 'Pending')
                                    <button class="btn btn-sm btn-success select-btn" onclick="selectCandidate({{ $interview->id }})">
                                        <i class="fas fa-check"></i> Select
                                    </button>
                                    <button class="btn btn-sm btn-danger reject-btn" onclick="rejectCandidate({{ $interview->id }})">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @else
                                    <span class="text-muted">Process Complete</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No interviews found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $interviews->links() }}
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Candidate</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <div class="form-group">
                        <label>Rejection Reason:</label>
                        <textarea class="form-control" id="rejectionReason" rows="3" required placeholder="Please provide reason for rejection..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmReject()">Reject Candidate</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.page-header h1 {
    margin: 0;
    font-size: 1.5rem;
}

.content-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow-x: auto;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table {
    width: 100%;
    min-width: 800px;
    margin-bottom: 0;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px 8px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    white-space: nowrap;
}

.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    display: inline-block;
}

.badge-info { background-color: #28a745 !important; color: white !important; }
.badge-success { background-color: #28a745 !important; color: white !important; }
.badge-warning { background-color: #ffc107 !important; color: #212529 !important; }
.badge-primary { background-color: #28a745 !important; color: white !important; }
.badge-danger { background-color: #dc3545 !important; color: white !important; }
.badge-secondary { background-color: #6c757d; color: white; }

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-success:hover {
    background-color: #218838;
    transform: translateY(-1px);
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
    transform: translateY(-1px);
}

.btn-primary {
    background-color: #2eacb3;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}

.alert {
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

/* Modal Styles */
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

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-dialog {
    max-width: 500px;
    width: 90%;
}

.modal-content {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.modal-header {
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6c757d;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
}
</style>
@endsection

@section('scripts')
<script>
let currentInterviewId = null;

function markCompleted(interviewId) {
    if (confirm('Mark this interview as completed?')) {
        fetch(`/admin/interviews/${interviewId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update status
                document.getElementById(`status-${interviewId}`).innerHTML = 
                    '<span class="badge badge-warning">⏳ Pending Decision</span>';
                
                // Update actions
                document.getElementById(`actions-${interviewId}`).innerHTML = `
                    <button class="btn btn-sm btn-success select-btn" onclick="selectCandidate(${interviewId})">
                        <i class="fas fa-check"></i> Select
                    </button>
                    <button class="btn btn-sm btn-danger reject-btn" onclick="rejectCandidate(${interviewId})">
                        <i class="fas fa-times"></i> Reject
                    </button>
                `;
                
                showAlert('Interview marked as completed!', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating interview status', 'error');
        });
    }
}

function selectCandidate(interviewId) {
    if (confirm('Select this candidate?')) {
        updateResult(interviewId, 'Selected');
    }
}

function rejectCandidate(interviewId) {
    currentInterviewId = interviewId;
    document.getElementById('rejectModal').classList.add('show');
    document.getElementById('rejectModal').style.display = 'flex';
}

function confirmReject() {
    const reason = document.getElementById('rejectionReason').value.trim();
    if (!reason) {
        alert('Please provide a rejection reason');
        return;
    }
    
    updateResult(currentInterviewId, 'Rejected', reason);
    closeModal();
}

function updateResult(interviewId, result, rejectionReason = null) {
    const data = {
        result: result
    };
    
    if (rejectionReason) {
        data.rejection_reason = rejectionReason;
    }
    
    fetch(`/admin/interviews/${interviewId}/result`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update status
            const statusBadge = result === 'Selected' 
                ? '<span class="badge badge-success">✅ Selected</span>'
                : '<span class="badge badge-danger">❌ Rejected</span>';
            
            document.getElementById(`status-${interviewId}`).innerHTML = statusBadge;
            
            // Update actions
            document.getElementById(`actions-${interviewId}`).innerHTML = 
                '<span class="text-muted">Process Complete</span>';
            
            const message = result === 'Selected' ? 'Candidate selected!' : 'Candidate rejected!';
            showAlert(message, 'success');
            
            // Redirect to selected employees page if candidate was selected
            if (result === 'Selected') {
                setTimeout(() => {
                    window.location.href = '/admin/interviews/selected';
                }, 1500);
            }
        } else {
            showAlert(data.message || 'Error updating result', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error updating result: ' + error.message, 'error');
    });
}

function closeModal() {
    document.getElementById('rejectModal').classList.remove('show');
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectionReason').value = '';
    currentInterviewId = null;
}

function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `<div class="alert ${alertClass}">${message}</div>`;
    
    const existingAlert = document.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    document.querySelector('.page-header').insertAdjacentHTML('afterend', alertHtml);
    
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) alert.remove();
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with close button
document.querySelector('.close').addEventListener('click', closeModal);
</script>
@endsection