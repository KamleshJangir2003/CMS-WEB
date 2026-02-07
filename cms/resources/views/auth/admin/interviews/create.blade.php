@extends('auth.layouts.app')

@section('title', 'Schedule Interview')

@section('content')
<div class="main-content">
    <div class="page-header">
        <h1>📅 Schedule Interview</h1>
        <a href="{{ route('admin.interviews.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <form action="{{ route('admin.interviews.store') }}" method="POST">
            @csrf
            
            <!-- Candidate Info -->
            <div class="section">
                <div class="section-title">Candidate Information</div>
                <div class="row">
                    @if($lead)
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <div class="col">
                            <label>Candidate Name</label>
                            <input type="text" value="{{ $lead->name }}" class="readonly" readonly>
                        </div>
                        <div class="col">
                            <label>Email</label>
                            <input type="text" value="{{ $lead->email }}" class="readonly" readonly>
                        </div>
                        <div class="col">
                            <label>Job Role</label>
                            <input type="text" value="{{ $lead->role }}" class="readonly" readonly>
                        </div>
                    @else
                        <div class="col">
                            <label>Select Candidate</label>
                            <select name="lead_id" required onchange="updateCandidateInfo(this)">
                                <option value="">Choose a candidate</option>
                                @foreach($leads as $leadOption)
                                    <option value="{{ $leadOption->id }}" 
                                            data-name="{{ $leadOption->name }}" 
                                            data-email="{{ $leadOption->email }}" 
                                            data-role="{{ $leadOption->role }}">
                                        {{ $leadOption->name }} - {{ $leadOption->role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label>Candidate Name</label>
                            <input type="text" id="candidate_name" class="readonly" readonly>
                        </div>
                        <div class="col">
                            <label>Email</label>
                            <input type="text" id="candidate_email" class="readonly" readonly>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Interview Details -->
            <div class="section">
                <div class="section-title">Interview Details</div>

                <label>Interview Round</label>
                <div class="radio-group">
                    <label><input type="radio" name="interview_round" value="HR" {{ $nextRound == 'HR' ? 'checked' : '' }} required> HR</label>
                    <label><input type="radio" name="interview_round" value="Technical" {{ $nextRound == 'Technical' ? 'checked' : '' }} required> Technical</label>
                    <label><input type="radio" name="interview_round" value="Manager" {{ $nextRound == 'Manager' ? 'checked' : '' }} required> Manager</label>
                    <label><input type="radio" name="interview_round" value="Final" {{ $nextRound == 'Final' ? 'checked' : '' }} required> Final</label>
                </div>

                <div class="row" style="margin-top:15px;">
                    <div class="col">
                        <label>Interview Date</label>
                        <input type="date" name="interview_date" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col">
                        <label>Start Time</label>
                        <input type="time" name="start_time" required>
                    </div>
                    <div class="col">
                        <label>End Time</label>
                        <input type="time" name="end_time" required>
                    </div>
                </div>

                <div class="row" style="margin-top:15px;">
                    <div class="col">
                        <label>Interviewer</label>
                        <select name="interviewer" required>
                            <option value="">Select Interviewer</option>
                            @foreach($interviewers as $interviewer)
                                <option value="{{ $interviewer }}">{{ $interviewer }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Interview Mode</label>
                        <select name="interview_mode" required onchange="toggleMeetingSection(this.value)">
                            <option value="Online">Online</option>
                            <option value="Offline">Offline</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Meeting Link -->
            <div class="section" id="meeting-section">
                <div class="section-title">Meeting Link</div>

                <label>Meeting Platform</label>
                <div class="radio-group">
                    <label><input type="radio" name="meeting_platform" value="Google Meet"> Google Meet</label>
                    <label><input type="radio" name="meeting_platform" value="Zoom"> Zoom</label>
                    <label><input type="radio" name="meeting_platform" value="Teams"> Teams</label>
                </div>

                <div class="meeting-box" style="margin-top:12px;">
                    <input type="text" name="meeting_link" id="meeting_link" placeholder="Meeting link will appear here" readonly>
                    <button type="button" class="generate-btn" onclick="generateMeetingLink()">Generate Link</button>
                </div>
            </div>

            <!-- Notes -->
            <div class="section">
                <div class="section-title">Instructions / Notes</div>
                <textarea name="instructions" placeholder="Please join 10 minutes early. Keep portfolio ready."></textarea>
            </div>

            <!-- Notifications -->
            <div class="section">
                <div class="section-title">Notifications</div>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="email_candidate" checked> Email to Candidate</label>
                    <label><input type="checkbox" name="email_interviewer" checked> Email to Interviewer</label>
                    <label><input type="checkbox" name="whatsapp_notification"> WhatsApp Notification</label>
                </div>
            </div>

            <!-- Actions -->
            <div class="actions">
                <a href="{{ route('admin.interviews.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Schedule Interview</button>
            </div>
        </form>
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

input, select, textarea{
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

.radio-group, .checkbox-group{
    display:flex;
    gap:20px;
    margin-top:8px;
    flex-wrap: wrap;
}

.radio-group label,
.checkbox-group label{
    font-size:14px;
    cursor:pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.radio-group input,
.checkbox-group input{
    width: auto;
    margin: 0;
}

.meeting-box{
    display:flex;
    gap:10px;
    align-items:center;
}

.meeting-box input{
    flex:1;
}

.generate-btn{
    padding:10px 16px;
    background:#4f46e5;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
    white-space: nowrap;
}

.generate-btn:hover{
    background:#4338ca;
}

.actions{
    display:flex;
    gap:15px;
    justify-content:flex-end;
    margin-top:30px;
}

.btn-primary{
    padding:12px 22px;
    background:#16a34a;
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
}

.btn-secondary{
    padding:12px 22px;
    background:#e5e7eb;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
    text-decoration: none;
    color: #333;
}

.btn-primary:hover{ background:#15803d; }
.btn-secondary:hover{ background:#d1d5db; }

.readonly{
    background:#f9fafb;
}

.alert {
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}
</style>

<script>
function updateCandidateInfo(select) {
    const option = select.options[select.selectedIndex];
    if (option.value) {
        document.getElementById('candidate_name').value = option.dataset.name;
        document.getElementById('candidate_email').value = option.dataset.email;
    } else {
        document.getElementById('candidate_name').value = '';
        document.getElementById('candidate_email').value = '';
    }
}

function toggleMeetingSection(mode) {
    const meetingSection = document.getElementById('meeting-section');
    if (mode === 'Offline') {
        meetingSection.style.display = 'none';
    } else {
        meetingSection.style.display = 'block';
    }
}

function generateMeetingLink() {
    const platform = document.querySelector('input[name="meeting_platform"]:checked');
    if (!platform) {
        alert('Please select a meeting platform first');
        return;
    }
    
    fetch('{{ route("admin.interviews.generate-link") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            platform: platform.value
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('meeting_link').value = data.link;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating meeting link');
    });
}
</script>
@endsection