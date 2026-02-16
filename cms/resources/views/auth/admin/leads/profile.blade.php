@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="card profile-card">
        <div class="card-header">
            <h4>Lead Profile</h4>
            <a href="{{ route('admin.leads.index') }}" class="back-btn">← Back to Leads</a>
        </div>

        <div class="card-body">
            <div class="profile-info">
                <div class="info-row">
                    <label>Number:</label>
                    <span>{{ $lead->number }}</span>
                </div>
                
                <div class="info-row">
                    <label>Name:</label>
                    <span>{{ $lead->name }}</span>
                </div>
                
                <div class="info-row">
                    <label>Status:</label>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $lead->condition_status)) }}">
                        {{ $lead->condition_status }}
                    </span>
                </div>
                
                <div class="info-row">
                    <label>Created:</label>
                    <span>{{ $lead->created_at->format('d M Y, h:i A') }}</span>
                </div>
                
                <div class="info-row">
                    <label>WhatsApp:</label>
                    <a href="https://wa.me/91{{ $lead->number }}" target="_blank" class="whatsapp-link">
                        <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>
                
                <div class="info-row">
                    <label>Resume:</label>
                    @if($lead->resume)
                        <a href="{{ route('admin.leads.resume.view', $lead->resume) }}" target="_blank" class="resume-link">
                            <i class="fa-solid fa-file-pdf"></i> View Resume
                        </a>
                    @else
                        <button class="upload-resume-btn" onclick="openUploadModal({{ $lead->id }})">
                            <i class="fa-solid fa-upload"></i> Upload Resume
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.main-content {
    padding: 20px;
}

.profile-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    max-width: 600px;
    margin: 0 auto;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
}

.back-btn {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
}

.back-btn:hover {
    background: #5a6268;
}

.profile-info {
    padding: 20px;
}

.info-row {
    display: flex;
    margin-bottom: 15px;
    align-items: center;
}

.info-row label {
    font-weight: 600;
    width: 120px;
    color: #555;
}

.info-row span {
    color: #333;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-not-interested {
    background: #f8d7da;
    color: #721c24;
}

.status-call-back {
    background: #fff3cd;
    color: #856404;
}

.status-picked {
    background: #d4edda;
    color: #155724;
}

.whatsapp-link {
    background: #25D366;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.whatsapp-link:hover {
    background: #1ebe5d;
}

.resume-link {
    background: #2eacb3;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.resume-link:hover {
    background: #0b5ed7;
}

.upload-resume-btn {
    background: #17a2b8;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.upload-resume-btn:hover {
    background: #138496;
}
</style>
@endsection

<!-- Resume Upload Modal -->
<div id="uploadModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Upload Resume</h5>
            <span class="close" onclick="closeUploadModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="resumeUploadForm" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="resume">Select Resume (PDF, DOC, DOCX - Max 5MB):</label>
                    <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-upload">Upload</button>
                    <button type="button" class="btn-cancel" onclick="closeUploadModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 0;
    border-radius: 8px;
    width: 400px;
    max-width: 90%;
}

.modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h5 {
    margin: 0;
    font-weight: 600;
}

.close {
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    color: #999;
}

.close:hover {
    color: #333;
}

.modal-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input[type="file"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-upload {
    background: #28a745;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-upload:hover {
    background: #218838;
}

.btn-cancel {
    background: #6c757d;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-cancel:hover {
    background: #5a6268;
}
</style>

<script>
let currentLeadId = null;

function openUploadModal(leadId) {
    currentLeadId = leadId;
    document.getElementById('uploadModal').style.display = 'block';
}

function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    document.getElementById('resumeUploadForm').reset();
    currentLeadId = null;
}

document.getElementById('resumeUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentLeadId) return;
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Uploading...';
    
    fetch(`/admin/leads/${currentLeadId}/resume`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Resume uploaded successfully!');
            location.reload();
        } else {
            alert('Upload failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Upload failed. Please try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Upload';
        closeUploadModal();
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('uploadModal');
    if (event.target == modal) {
        closeUploadModal();
    }
}
</script>