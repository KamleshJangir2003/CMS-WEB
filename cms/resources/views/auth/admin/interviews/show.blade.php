@extends('auth.layouts.app')

@section('title', 'Interview Details')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h1>📅 Interview Details</h1>
        <div>
            <a href="{{ route('admin.interviews.edit', $interview) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.interviews.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="container">
        <!-- Candidate Info -->
        <div class="section">
            <div class="section-title">Candidate Information</div>
            <div class="row">
                <div class="col">
                    <label>Candidate Name</label>
                    <input type="text" value="{{ $interview->candidate_name }}" class="readonly" readonly>
                </div>
                <div class="col">
                    <label>Email</label>
                    <input type="text" value="{{ $interview->candidate_email }}" class="readonly" readonly>
                </div>
                <div class="col">
                    <label>Job Role</label>
                    <input type="text" value="{{ $interview->job_role }}" class="readonly" readonly>
                </div>
            </div>
        </div>

        <!-- Interview Details -->
        <div class="section">
            <div class="section-title">Interview Details</div>
            
            <div class="row">
                <div class="col">
                    <label>Interview Round</label>
                    <input type="text" value="{{ $interview->interview_round }}" class="readonly" readonly>
                </div>
                <div class="col">
                    <label>Status</label>
                    <input type="text" value="{{ $interview->status }}" class="readonly" readonly>
                </div>
            </div>

            <div class="row" style="margin-top:15px;">
                <div class="col">
                    <label>Interview Date</label>
                    <input type="text" value="{{ $interview->interview_date->format('M d, Y') }}" class="readonly" readonly>
                </div>
                <div class="col">
                    <label>Start Time</label>
                    <input type="text" value="{{ date('g:i A', strtotime($interview->start_time)) }}" class="readonly" readonly>
                </div>
                <div class="col">
                    <label>End Time</label>
                    <input type="text" value="{{ date('g:i A', strtotime($interview->end_time)) }}" class="readonly" readonly>
                </div>
            </div>

            <div class="row" style="margin-top:15px;">
                <div class="col">
                    <label>Interviewer</label>
                    <input type="text" value="{{ $interview->interviewer }}" class="readonly" readonly>
                </div>
                <div class="col">
                    <label>Interview Mode</label>
                    <input type="text" value="{{ $interview->interview_mode }}" class="readonly" readonly>
                </div>
            </div>
        </div>

        @if($interview->interview_mode === 'Online' && $interview->meeting_link)
        <!-- Meeting Link -->
        <div class="section">
            <div class="section-title">Meeting Details</div>
            
            <div class="row">
                <div class="col">
                    <label>Meeting Platform</label>
                    <input type="text" value="{{ $interview->meeting_platform }}" class="readonly" readonly>
                </div>
                <div class="col">
                    <label>Meeting Link</label>
                    <div class="meeting-link-box">
                        <input type="text" value="{{ $interview->meeting_link }}" class="readonly" readonly>
                        <button type="button" class="copy-btn" onclick="copyToClipboard('{{ $interview->meeting_link }}')">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($interview->instructions)
        <!-- Notes -->
        <div class="section">
            <div class="section-title">Instructions / Notes</div>
            <textarea class="readonly" readonly>{{ $interview->instructions }}</textarea>
        </div>
        @endif

        <!-- Notifications -->
        <div class="section">
            <div class="section-title">Notification Settings</div>
            <div class="notification-status">
                <div class="notification-item">
                    <i class="fas fa-envelope {{ $interview->email_candidate ? 'text-success' : 'text-muted' }}"></i>
                    <span>Email to Candidate: {{ $interview->email_candidate ? 'Enabled' : 'Disabled' }}</span>
                </div>
                <div class="notification-item">
                    <i class="fas fa-envelope {{ $interview->email_interviewer ? 'text-success' : 'text-muted' }}"></i>
                    <span>Email to Interviewer: {{ $interview->email_interviewer ? 'Enabled' : 'Disabled' }}</span>
                </div>
                <div class="notification-item">
                    <i class="fab fa-whatsapp {{ $interview->whatsapp_notification ? 'text-success' : 'text-muted' }}"></i>
                    <span>WhatsApp Notification: {{ $interview->whatsapp_notification ? 'Enabled' : 'Disabled' }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <form action="{{ route('admin.interviews.destroy', $interview) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this interview?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Delete Interview</button>
            </form>
            <a href="{{ route('admin.interviews.edit', $interview) }}" class="btn-primary">Edit Interview</a>
        </div>
    </div>
</div>

<style>
body{
    margin:0;
    font-family: "Segoe UI", sans-serif;
    background:#f4f6f9;
}

.container{
    max-width:1100px;
    margin:30px auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.page-header div {
    display: flex;
    gap: 10px;
}

.section{
    margin-bottom:25px;
}

.section-title{
    font-size:16px;
    font-weight:600;
    margin-bottom:10px;
    color:#555;
}

.row{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
}

.col{
    flex:1;
    min-width:250px;
}

label{
    font-size:14px;
    display:block;
    margin-bottom:6px;
    color:#444;
}

input, textarea{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
    box-sizing: border-box;
}

textarea{
    resize:none;
    height:90px;
}

.readonly{
    background:#f9fafb;
}

.meeting-link-box {
    display: flex;
    gap: 10px;
    align-items: center;
}

.meeting-link-box input {
    flex: 1;
}

.copy-btn {
    padding: 10px 16px;
    background: #4f46e5;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    white-space: nowrap;
}

.copy-btn:hover {
    background: #4338ca;
}

.notification-status {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.notification-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

.text-success { color: #28a745; }
.text-muted { color: #6c757d; }

.actions{
    display:flex;
    gap:15px;
    justify-content:flex-end;
    margin-top:30px;
}

.btn-primary, .btn-danger, .btn-warning, .btn-secondary{
    padding:12px 22px;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-primary{ background:#16a34a; color:#fff; }
.btn-danger{ background:#dc3545; color:#fff; }
.btn-warning{ background:#ffc107; color:#000; }
.btn-secondary{ background:#e5e7eb; color:#333; }

.btn-primary:hover{ background:#15803d; }
.btn-danger:hover{ background:#c82333; }
.btn-warning:hover{ background:#e0a800; }
.btn-secondary:hover{ background:#d1d5db; }
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Meeting link copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection