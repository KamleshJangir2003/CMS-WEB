@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="card leads-card">
        <div class="card-header">
            <h4>Interested Candidates</h4>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to All Leads
            </a>
        </div>

        <div class="card-body">
            <!-- Search Bar -->
            <div class="search-container">
                <form method="GET" action="{{ route('admin.leads.interested') }}" class="search-form">
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" name="search" id="searchInput" placeholder="Search by name, number, or role..." value="{{ request('search') }}">
                        <button type="submit" class="search-btn">Search</button>
                        @if(request('search'))
                            <a href="{{ route('admin.leads.interested') }}" class="clear-btn">Clear</a>
                        @endif
                    </div>
                </form>
                <div class="results-info">
                    <span id="resultsCount">{{ $leads->total() }} results</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Updated At</th>
                            <th>WhatsApp</th>
                            <th>Location</th>
                            
                            <th>View Profile</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($leads->items() as $lead)
                        <tr>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->number }}</td>
                            <td>
                                <div>
                                    <span class="fw-medium">{{ $lead->role }}</span>
                                    @if($lead->platform)
                                        <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $lead->platform)) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-success">✅ Interested</span>
                            </td>
                            <td>
    {{ $lead->updated_at->format('d M Y') }} <br>
    {{ $lead->updated_at->format('h:i A') }}
</td>

                            <td>
                                <a href="https://wa.me/91{{ $lead->number }}" target="_blank" class="whatsapp-btn">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </td>
                            <td>
                                <a href="https://wa.me/91{{ $lead->number }}?text=https://share.google/Sl869XYivsY6LLJAE" target="_blank" class="location-btn">
                                    <i class="fa-solid fa-location-dot"></i>
                                </a>
                            </td>
                            <td>
                                @if($lead->resume)
                                    <a href="{{ route('admin.leads.resume.view', $lead->resume) }}" target="_blank" class="view-btn">
                                        View CV
                                    </a>
                                @else
                                    <button class="upload-btn" onclick="openUploadModal({{ $lead->id }})">
                                        Upload CV
                                    </button>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.interviews.create', ['lead_id' => $lead->id]) }}" class="schedule-btn">
                                    <i class="fas fa-calendar-plus"></i>Interview
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align:center;">No interested candidates found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($leads->hasPages())
            <div class="pagination-container">
                {{ $leads->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.leads-card {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
}

.btn-secondary {
    background: #6c757d;
    color: #fff;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 4px;
}

.btn-secondary:hover {
    background: #5a6268;
    color: #fff;
}

.table-responsive {
    overflow-x: auto;
}

.leads-table {
    width: 100%;
    border-collapse: collapse;
}

.leads-table th {
    background: #f1f3f5;
    padding: 14px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    white-space: nowrap;
}

.leads-table td {
    padding: 14px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
    font-size: 14px;
}

.leads-table tr:hover {
    background: #f9fafb;
}

.badge-success {
    background: #28a745;
    color: #fff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.view-btn {
    background: #0d6efd;
    color: #fff;
    padding: 6px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}

.view-btn:hover {
    background: #084298;
}

.whatsapp-btn {
    background: #25D366;
    color: #fff;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    font-size: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.whatsapp-btn:hover {
    background: #1ebe5d;
}

.location-btn {
    background: #dc3545;
    color: #fff;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    margin-left: 5px;
}

.location-btn:hover {
    background: #c82333;
    color: #fff;
}

.schedule-btn {
    background: #28a745;
    color: #fff;
    padding: 6px 12px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.schedule-btn:hover {
    background: #218838;
    color: #fff;
}

.upload-btn {
    background: #17a2b8;
    color: #fff;
    padding: 6px 16px;
    border-radius: 20px;
    border: none;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
}

.upload-btn:hover {
    background: #138496;
}

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

/* Search Styles */
.search-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
}

.search-form {
    flex: 1;
    max-width: 500px;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-size: 14px;
    z-index: 2;
}

.search-box input {
    flex: 1;
    padding: 10px 12px 10px 35px;
    border: 1px solid #ddd;
    border-radius: 8px 0 0 8px;
    font-size: 14px;
    background: #fff;
    border-right: none;
}

.search-box input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.search-btn {
    padding: 10px 16px;
    background: #007bff;
    color: #fff;
    border: 1px solid #007bff;
    border-radius: 0;
    font-size: 14px;
    cursor: pointer;
    white-space: nowrap;
}

.search-btn:hover {
    background: #0056b3;
    border-color: #0056b3;
}

.clear-btn {
    padding: 10px 12px;
    background: #6c757d;
    color: #fff;
    text-decoration: none;
    border-radius: 0 8px 8px 0;
    font-size: 14px;
    white-space: nowrap;
    display: flex;
    align-items: center;
}

.clear-btn:hover {
    background: #545b62;
    color: #fff;
}

.results-info {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

/* Pagination Styles */
.pagination-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

.pagination {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 5px;
}

.page-item {
    display: flex;
}

.page-link {
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    color: #007bff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.2s;
}

.page-link:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.page-item.active .page-link {
    background: #007bff;
    border-color: #007bff;
    color: #fff;
}

.page-item.disabled .page-link {
    color: #6c757d;
    background: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .search-container {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-form {
        max-width: 100%;
    }
    
    .search-box {
        flex-wrap: wrap;
    }
    
    .search-box input {
        border-radius: 8px;
        border-right: 1px solid #ddd;
        margin-bottom: 8px;
    }
    
    .search-btn, .clear-btn {
        border-radius: 8px;
        flex: 1;
    }
    
    .results-info {
        text-align: center;
        margin-top: 10px;
    }
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

// Search functionality (client-side filter for current page)
document.getElementById('searchInput').addEventListener('input', function() {
    // Only do client-side filtering if no server search is active
    if (!this.form.querySelector('input[name="search"]').value) {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.leads-table tbody tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            if (row.cells.length === 1) return; // Skip "no results" row
            
            const name = row.cells[0].textContent.toLowerCase();
            const number = row.cells[1].textContent.toLowerCase();
            const role = row.cells[2].textContent.toLowerCase();
            
            const matches = name.includes(searchTerm) || 
                           number.includes(searchTerm) || 
                           role.includes(searchTerm);
            
            if (matches) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        document.getElementById('resultsCount').textContent = visibleCount + ' results (filtered)';
    }
});
</script>