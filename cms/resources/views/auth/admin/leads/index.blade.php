@extends('auth.layouts.app')

@section('header')
<div class="header-upload">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.leads.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form" id="uploadForm">
        @csrf
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="Developer">Developer</option>
            <option value="Designer">Designer</option>
            <option value="Manager">Manager</option>
            <option value="Tester">Tester</option>
        </select>
        <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required id="fileInput">
        <button type="submit" class="upload-btn" id="uploadBtn">
            <span class="btn-text">Upload Excel</span>
            <span class="btn-loading" style="display: none;">Uploading...</span>
        </button>
        <small class="text-muted">Excel format: Column A = Number, Column B = Name, Column C = Role (Max: 10MB)</small>
    </form>
</div>
@endsection

@section('content')
<div class="main-content">

    <div class="card leads-card">
        <div class="card-header">
            <h4>Leads List</h4>
            <a href="{{ route('admin.callbacks.index') }}" class="btn btn-info btn-sm">
                <i class="fa-solid fa-phone me-1"></i> View Callbacks
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Condition Status</th>
                            <th>Role</th>
                            <!-- <th>Interview Status</th> -->
                            <th>WhatsApp</th>
                            <th>View Profile</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($leads as $lead)
                        <tr>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->number }}</td>

                            <td>
                                <select class="status-select" data-id="{{ $lead->id }}">
                                    <option value="" selected>Select Status</option>
                                    <option value="Not Interested">Not Interested</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="Picked">Pickup</option>
                                    <option value="Intrested">Intrested</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Wrong Number">Wrong Number</option>
                                </select>
                            </td>

                            <td>{{ $lead->role }}</td>

                            <!-- <td>
                                @if($lead->final_result == 'Selected')
                                    <span class="badge badge-success">✅ Selected</span>
                                @elseif($lead->final_result == 'Rejected')
                                    <span class="badge badge-danger">❌ Rejected</span>
                                    @if($lead->rejection_reason)
                                        <small class="text-muted d-block">{{ Str::limit($lead->rejection_reason, 30) }}</small>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">⏳ Pending</span>
                                @endif
                            </td> -->

                            <td>
                                <a href="https://wa.me/91{{ $lead->number }}" target="_blank" class="whatsapp-btn">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </td>

                            <td>
                                <a href="{{ route('admin.leads.cv', $lead->id) }}" class="view-btn">
                                    View CV
                                </a>
                            </td>

                            <td>
                                @if($lead->final_result == 'Pending')
                                    <a href="{{ route('admin.interviews.create', ['lead_id' => $lead->id]) }}" class="schedule-btn">
                                        <i class="fas fa-calendar-plus"></i> Schedule Interview
                                    </a>
                                    
                                @else
                                    <span class="text-muted">Process Complete</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center;">No leads found</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

{{-- ================= STYLES ================= --}}
<style>
    /* ================= MAIN CONTENT ================= */


/* ================= HEADER UPLOAD ================= */


/* ================= ALERTS ================= */
.alert {
    padding: 10px 14px;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 14px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* ================= CARD ================= */
.leads-card {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

/* Card Header */
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

.btn-info {
    background: #17a2b8;
    color: #fff;
    text-decoration: none;
}

.btn-info:hover {
    background: #138496;
    color: #fff;
}

/* ================= TABLE ================= */
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

/* ================= STATUS DROPDOWN ================= */
.status-select {
    padding: 6px 14px;
    border-radius: 16px;
    border: 1px solid #ccc;
    width: 160px;
    font-size: 13px;
    font-weight: 500;
    background-color: #fff;
}


/* ================= VIEW BUTTON ================= */
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

/* ================= WHATSAPP BUTTON ================= */
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
}

.whatsapp-btn:hover {
    background: #1ebe5d;
}

/* ================= SCHEDULE BUTTON ================= */
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
    text-decoration: none;
}

/* ================= BADGES ================= */
.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

.badge-secondary {
    background: #e2e3e5;
    color: #383d41;
}

/* ================= MOBILE FIX ================= */
@media (max-width: 992px) {
    .main-content {
        margin-left: 0;
        width: 100%;
    }

    .header-upload,
    .leads-card {
        max-width: 100%;
    }

    .upload-form {
        flex-direction: column;
        align-items: stretch;
    }

    .status-select {
        width: 100%;
    }
}

</style>

{{-- ================= SCRIPT ================= --}}
<script>
// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Status update functionality
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function () {
        const leadId = this.dataset.id;
        const status = this.value;
        const row = this.closest('tr');

        fetch(`/admin/leads/${leadId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ condition_status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from table for any status change
                row.remove();
                
                if (status === 'Call Back') {
                    showNotification('Lead moved to callbacks page', 'info');
                    
                    // Update callback count in sidebar
                    if (window.updateCallbackCount) {
                        window.updateCallbackCount();
                    }
                } else {
                    showNotification(`Status updated to ${status}`, 'success');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
});

// File upload with loading state
document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('fileInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const btnText = uploadBtn.querySelector('.btn-text');
    const btnLoading = uploadBtn.querySelector('.btn-loading');
    
    // Check if file is selected
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('Please select a file to upload.');
        return;
    }
    
    // Check file size (10MB = 10 * 1024 * 1024 bytes)
    const maxSize = 10 * 1024 * 1024;
    if (fileInput.files[0].size > maxSize) {
        e.preventDefault();
        alert('File size must be less than 10MB.');
        return;
    }
    
    // Check file extension
    const fileName = fileInput.files[0].name.toLowerCase();
    const validExtensions = ['.xlsx', '.xls', '.csv'];
    const hasValidExtension = validExtensions.some(ext => fileName.endsWith(ext));
    
    if (!hasValidExtension) {
        e.preventDefault();
        alert('Please select a valid Excel or CSV file.');
        return;
    }
    
    // Show loading state
    uploadBtn.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline';
    
    // Re-enable button after 30 seconds (timeout)
    setTimeout(() => {
        uploadBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoading.style.display = 'none';
    }, 30000);
});

// File input change event for validation feedback
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    const fileName = file.name.toLowerCase();
    const validExtensions = ['.xlsx', '.xls', '.csv'];
    const hasValidExtension = validExtensions.some(ext => fileName.endsWith(ext));
    
    if (!hasValidExtension) {
        alert('Please select a valid Excel or CSV file.');
        e.target.value = '';
        return;
    }
    
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
        alert('File size must be less than 10MB.');
        e.target.value = '';
        return;
    }
});
</script>
@endsection
