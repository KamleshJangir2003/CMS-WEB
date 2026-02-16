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
                    <input type="hidden" name="candidate_name" value="{{ $lead->name }}">

                        <div class="col">
                            <label>Candidate Name</label>
                            <input type="text" value="{{ $lead->name }}" class="readonly" readonly>
                        </div>
                        <div class="col">
                            <label>Email</label>
                            <input type="email" name="candidate_email" value="{{ $lead->email }}" required>
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
                            <input type="email" name="candidate_email" id="candidate_email" required>
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
                        <label>Interviewer Email</label>
                        <input type="email" name="interviewer_email" required placeholder="interviewer@company.com">
                    </div>
                    <div class="col">
                        <label>Interviewer Phone</label>
                        <input type="tel" name="interviewer_phone" required placeholder="+91 9876543210">
                    </div>
                </div>

                <div class="row" style="margin-top:15px;">
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
                    <label><input type="radio" name="meeting_platform" value="Google Meet" onchange="toggleLinkInput()"> Google Meet</label>
                    <label><input type="radio" name="meeting_platform" value="Zoom" onchange="toggleLinkInput()"> Zoom</label>
                    <label><input type="radio" name="meeting_platform" value="Teams" onchange="toggleLinkInput()"> Teams</label>
                </div>

                <div class="meeting-box" style="margin-top:12px;">
                    <input type="text" name="meeting_link" id="meeting_link" placeholder="Paste your meeting link here or generate one" required>
                    <button type="button" class="generate-btn" onclick="generateMeetingLink()">Generate Link</button>
                </div>
                
                <div class="meeting-note" style="margin-top:10px; padding:10px; background:#fff3cd; border:1px solid #ffeaa7; border-radius:4px; font-size:13px;">
                    <strong>Note:</strong> 
                    <div id="platform-instructions">
                        <div id="meet-note" style="display:none;">For Google Meet: Create meeting at <a href="https://meet.google.com" target="_blank">meet.google.com</a></div>
                        <div id="zoom-note" style="display:none;">For Zoom: Create meeting at <a href="https://zoom.us" target="_blank">zoom.us</a> → Schedule Meeting</div>
                        <div id="teams-note" style="display:none;">For Teams: Create meeting at <a href="https://teams.microsoft.com" target="_blank">teams.microsoft.com</a> → Calendar → New Meeting</div>
                        <div id="default-note">Select a platform to see instructions</div>
                    </div>
                </div>
            </div>
             <!-- Interviewer -->
             <div class="section">
                <div class="section-title">Interviewer / Notes</div>
                <textarea name="interviewer_notes" placeholder="Notes for interviewer only"></textarea>
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
                <button type="submit" class="btn-primary" onclick="return handleFormSubmit()">Schedule Interview</button>
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

.generate-btn{
    background:#2eacb3;
    color:#fff;
    border:none;
    padding:10px 15px;
    border-radius:6px;
    cursor:pointer;
    font-size:14px;
    white-space:nowrap;
}

.generate-btn:hover{
    background:#0056b3;
}

.readonly{
    background:#f8f9fa;
    color:#6c757d;
}

.actions{
    display:flex;
    gap:15px;
    justify-content:flex-end;
    margin-top:30px;
}

.btn-primary, .btn-secondary{
    padding:12px 25px;
    border:none;
    border-radius:6px;
    font-size:14px;
    cursor:pointer;
    text-decoration:none;
    display:inline-block;
}

.btn-primary{
    background:#28a745;
    color:#fff;
}

.btn-secondary{
    background:#6c757d;
    color:#fff;
}

.btn-primary:hover{
    background:#218838;
}

.btn-secondary:hover{
    background:#545b62;
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
    if (mode === 'Online') {
        meetingSection.style.display = 'block';
    } else {
        meetingSection.style.display = 'none';
    }
}

function toggleLinkInput() {
    const platform = document.querySelector('input[name="meeting_platform"]:checked');
    const linkInput = document.getElementById('meeting_link');
    
    // Hide all notes
    document.getElementById('meet-note').style.display = 'none';
    document.getElementById('zoom-note').style.display = 'none';
    document.getElementById('teams-note').style.display = 'none';
    document.getElementById('default-note').style.display = 'none';
    
    if (platform) {
        switch(platform.value) {
            case 'Google Meet':
                linkInput.placeholder = 'Create meeting at meet.google.com and paste link here';
                document.getElementById('meet-note').style.display = 'block';
                break;
            case 'Zoom':
                linkInput.placeholder = 'Create meeting at zoom.us and paste link here';
                document.getElementById('zoom-note').style.display = 'block';
                break;
            case 'Teams':
                linkInput.placeholder = 'Create meeting at teams.microsoft.com and paste link here';
                document.getElementById('teams-note').style.display = 'block';
                break;
        }
    } else {
        linkInput.placeholder = 'Select platform first';
        document.getElementById('default-note').style.display = 'block';
    }
}

function generateMeetingLink() {
    const platform = document.querySelector('input[name="meeting_platform"]:checked');
    if (!platform) {
        alert('Please select a meeting platform first');
        return;
    }
    
    const interviewDate = document.querySelector('input[name="interview_date"]').value;
    const startTime = document.querySelector('input[name="start_time"]').value;
    const endTime = document.querySelector('input[name="end_time"]').value;
    
    switch(platform.value) {
        case 'Google Meet':
            if (!interviewDate || !startTime || !endTime) {
                alert('Please fill interview date and time first');
                return;
            }
            
            // Generate Google Meet style link
            const meetingId = generateGoogleMeetId();
            const link = `https://meet.google.com/${meetingId}`;
            
            // Format the meeting info
            const dateObj = new Date(interviewDate);
            const formattedDate = dateObj.toLocaleDateString('en-US', { 
                month: 'long', 
                day: 'numeric', 
                year: 'numeric' 
            });
            
            const meetingInfo = `Kwikster Interview\n${formattedDate}\nTime: ${startTime} - ${endTime}\nTime zone: Asia/Kolkata\nGoogle Meet joining info\nVideo call link: ${link}`;
            
            document.getElementById('meeting_link').value = link;
            
            // Show formatted meeting info
            alert(`Meeting Created!\n\n${meetingInfo}`);
            break;
            
        case 'Zoom':
            alert('For Zoom:\n1. Go to zoom.us and sign in\n2. Click "Schedule a Meeting"\n3. Copy the meeting link\n4. Paste it here');
            window.open('https://zoom.us', '_blank');
            break;
            
        case 'Teams':
            alert('For Microsoft Teams:\n1. Go to teams.microsoft.com\n2. Click Calendar → New Meeting\n3. Copy the meeting link\n4. Paste it here');
            window.open('https://teams.microsoft.com', '_blank');
            break;
    }
}

function generateGoogleMeetId() {
    // Generate Google Meet style ID (xxx-xxxx-xxx)
    const chars = 'abcdefghijklmnopqrstuvwxyz';
    let result = '';
    
    // First part: 3 characters
    for (let i = 0; i < 3; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    result += '-';
    
    // Second part: 4 characters
    for (let i = 0; i < 4; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    result += '-';
    
    // Third part: 3 characters
    for (let i = 0; i < 3; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    return result;
}

function handleFormSubmit() {
    const mode = document.querySelector('select[name="interview_mode"]').value;
    if (mode === 'Online') {
        const meetingLink = document.getElementById('meeting_link').value;
        if (!meetingLink) {
            alert('Please generate a meeting link for online interview');
            return false;
        }
    }
    return true;
}
</script>


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

function handleFormSubmit() {
    const interviewMode = document.querySelector('select[name="interview_mode"]').value;
    const meetingLink = document.getElementById('meeting_link').value;
    
    if (interviewMode === 'Online' && !meetingLink.trim()) {
        alert('Please enter a meeting link for online interview');
        return false;
    }
    
    return true;
}
</script>
@endsection