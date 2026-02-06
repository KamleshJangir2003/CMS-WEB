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
        <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" required id="fileInput">
        <button type="submit" class="upload-btn" id="uploadBtn">
            <span class="btn-text">Upload Excel</span>
            <span class="btn-loading" style="display: none;">Uploading...</span>
        </button>
        <small class="text-muted">Excel format: Column A = Number, Column B = Name (Max: 10MB)</small>
    </form>
</div>
@endsection

@section('content')
<div class="main-content">

    <div class="card leads-card">
        <div class="card-header">
            <h4>Leads List</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Condition Status</th>
                            <th>Role</th>
                            <th>WhatsApp</th>
                            <th>View Profile</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($leads as $lead)
                        <tr>
                            <td>{{ $lead->number }}</td>
                            <td>{{ $lead->name }}</td>

                            <td>
                                <select class="status-select" data-id="{{ $lead->id }}">
                                    <option value="Not Interested" {{ $lead->condition_status == 'Not Interested' ? 'selected' : '' }}>Not Interested</option>
                                    <option value="Call Back" {{ $lead->condition_status == 'Call Back' ? 'selected' : '' }}>Call Back</option>
                                    <option value="Picked" {{ $lead->condition_status == 'Picked' ? 'selected' : '' }}>Picked</option>
                                </select>
                            </td>

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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">No leads found</td>
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
.main-content {
    margin-left: 260px;                 /* sidebar width */
    padding: 24px;
    width: calc(100% - 260px);
    box-sizing: border-box;
    background: #f5f6fa;
}

/* ================= HEADER UPLOAD ================= */
.header-upload {
    max-width: 1200px;
    margin: 0 auto 20px auto;
    background: #fff;
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.06);
}

.upload-form {
    display: flex;
    gap: 12px;
    align-items: center;
}

.upload-form input[type="file"] {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
    flex: 1;
}

.upload-btn {
    background: #28a745;
    color: #fff;
    padding: 9px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
}

.upload-btn:hover {
    background: #218838;
}

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
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
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
    border-radius: 20px;
    border: 1px solid #ccc;
    width: 150px;
    font-size: 13px;
    font-weight: 500;
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
// Status update functionality
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function () {
        const leadId = this.dataset.id;
        const status = this.value;

        fetch(`/admin/leads/${leadId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ condition_status: status })
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
