@extends('auth.layouts.app')

@section('styles')
<style>
.status-select {
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    font-size: 14px;
}

.status-select:focus {
    outline: none;
    border-color: #2eacb3;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
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
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h5 {
    margin: 0;
    font-size: 18px;
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

.modal-body textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    resize: vertical;
    font-family: inherit;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-cancel, .btn-save {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-cancel {
    background: #6c757d;
    color: white;
}

.btn-save {
    background: #2eacb3;
    color: white;
}

.btn-cancel:hover {
    background: #5a6268;
}

.btn-save:hover {
    background: #0056b3;
}

/* Row fade out animation */
.fade-out {
    opacity: 0;
    transition: opacity 0.5s ease-out;
}
</style>
@endsection

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
            <!-- Search Bar -->
            <div class="search-container">
                <form method="GET" action="{{ route('admin.leads.index') }}" class="search-form" id="searchForm">
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" name="search" id="searchInput" placeholder="Search by name, number, or role..." value="{{ request('search') }}" autocomplete="off">
                        <!-- <button type="submit" class="search-btn">Search</button> -->
                        @if(request('search'))
                            <a href="{{ route('admin.leads.index') }}" class="clear-btn">Clear</a>
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
                            <th>Condition Status</th>
                            <th>Role</th>
                            <!-- <th>Interview Status</th> -->
                            <th>WhatsApp</th>
                            <th>Send Location</th>
                            <th>View Profile</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">
                        @forelse($leads->items() as $lead)
                        <tr class="lead-row" data-name="{{ strtolower($lead->name) }}" data-number="{{ $lead->number }}" data-role="{{ strtolower($lead->role) }}">
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->number }}</td>

                            <td>
                                <select class="status-select" data-id="{{ $lead->id }}">
                                    <option value="" selected>Select Status</option>
                                    <option value="Not Interested">Not Interested</option>
                                    <option value="Call Back">Call Back</option>
                                    <!-- <option value="Picked">Pickup</option> -->
                                    <option value="Intrested">Intrested</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Wrong Number">Wrong Number</option>
                                </select>
                            </td>

                            <td>
                                <div>
                                    <span class="fw-medium">{{ $lead->role }}</span>
                                    @if($lead->platform)
                                        <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $lead->platform)) }}</small>
                                    @endif
                                </div>
                            </td>

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

                            @php
$message = "We have an exciting job opportunity for you.\n\n".
"Job Details\n\n".
"💼 Job Title: {$lead->role}\n".
"🏤 Company Name: Kwikster Innovative Optimisations Pvt Ltd.\n".
"📌 Location: 21/284, Kaveri Path, Sector 21, Mansarovar, Jaipur, Rajasthan 302020\n".
"💰 Salary: ₹15,000 – ₹25,000 per month\n\n".
"Reply \"Yes\" to this message to know more.\n\n".
"Best Regards,\n".
"HR Team\n".
"Kwikster Innovative Optimisations Pvt Ltd.";
@endphp

<td>
    <a href="https://wa.me/91{{ $lead->number }}?text={{ urlencode($message) }}"
       target="_blank"
       class="whatsapp-btn">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
</td>


                            <td>
                                <a href="https://wa.me/91{{ $lead->number }}?text=https://share.google/j1HSKuOut2VpIweKA%0A%0AWe%20appreciate%20your%20interest.%0ALooking%20forward%20to%20assisting%20you%20soon." target="_blank" class="location-btn">
                                    <i class="fa-solid fa-location-dot"></i> Send Location
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
                        <tr id="noResults">
                            <td colspan="9" style="text-align:center;">No leads found</td>
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

<!-- Status Reason Modal -->
<div id="statusModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Status Change Reason</h5>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <p>Please provide a reason for changing status to <strong id="statusText"></strong>:</p>
            <textarea id="reasonText" placeholder="Enter reason..." rows="4"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel">Cancel</button>
            <button type="button" class="btn-save">Save</button>
        </div>
    </div>
</div>

{{-- ================= JAVASCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('tableBody');
    const resultsCount = document.getElementById('resultsCount');
    const leadRows = document.querySelectorAll('.lead-row');
    const noResults = document.getElementById('noResults');
    
    // Auto search function
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        leadRows.forEach(row => {
            const name = row.dataset.name || '';
            const number = row.dataset.number || '';
            const role = row.dataset.role || '';
            
            const isMatch = name.includes(searchTerm) || 
                          number.includes(searchTerm) || 
                          role.includes(searchTerm);
            
            if (isMatch || searchTerm === '') {
                row.style.display = 'table-row';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update results count
        resultsCount.textContent = `${visibleCount} results`;
        
        // Show/hide no results message
        if (noResults) {
            if (visibleCount === 0 && searchTerm !== '') {
                noResults.style.display = 'table-row';
                noResults.innerHTML = '<td colspan="9" style="text-align:center;">No matching results found</td>';
            } else if (leadRows.length === 0) {
                noResults.style.display = 'table-row';
            } else {
                noResults.style.display = 'none';
            }
        }
    }
    
    // Add event listener for real-time search
    searchInput.addEventListener('input', function() {
        performSearch();
    });
    
    // Clear search on Escape key
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Escape') {
            searchInput.value = '';
            performSearch();
        }
    });
    
    // Initial search if there's a value
    if (searchInput.value) {
        performSearch();
    }
    
    // Status handling code
    const statusSelects = document.querySelectorAll('.status-select');
    const modal = document.getElementById('statusModal');
    const statusText = document.getElementById('statusText');
    const reasonText = document.getElementById('reasonText');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.querySelector('.btn-cancel');
    const saveBtn = document.querySelector('.btn-save');
    
    let currentLeadId = null;
    let currentStatus = null;
    let currentRow = null;
    
    // Handle status change
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const leadId = this.dataset.id;
            const status = this.value;
            const row = this.closest('tr');
            
            if (!status) return;
            
            currentLeadId = leadId;
            currentStatus = status;
            currentRow = row;
            
            // Show modal for statuses that require reason
            if (['Not Interested', 'Call Back', 'Rejected'].includes(status)) {
                statusText.textContent = status;
                reasonText.value = '';
                modal.style.display = 'flex';
            } else {
                // Update status directly for other statuses
                updateLeadStatus(leadId, status, '', row);
            }
        });
    });
    
    // Modal close handlers
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    
    // Save button handler
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const reason = reasonText.value.trim();
            if (!reason) {
                alert('Please provide a reason');
                return;
            }
            
            updateLeadStatus(currentLeadId, currentStatus, reason, currentRow);
            closeModal();
        });
    }
    
    // Close modal when clicking outside
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }
    
    function closeModal() {
        if (modal) modal.style.display = 'none';
        if (currentRow) {
            const select = currentRow.querySelector('.status-select');
            select.value = '';
        }
        currentLeadId = null;
        currentStatus = null;
        currentRow = null;
    }
    
    function updateLeadStatus(leadId, status, reason, row) {
        const formData = new FormData();
        formData.append('condition_status', status);
        formData.append('reason', reason);
        formData.append('_token', '{{ csrf_token() }}');
        
        fetch(`/admin/leads/${leadId}/status`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show status name on screen
                showStatusMessage(status);
                
                // Immediately remove the row
                row.remove();
                updateResultsCount();
            } else {
                alert(data.message || 'Error updating status');
                const select = row.querySelector('.status-select');
                select.value = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error occurred');
            const select = row.querySelector('.status-select');
            select.value = '';
        });
    }
    
    function updateResultsCount() {
        const tbody = document.querySelector('.leads-table tbody');
        const rows = tbody.querySelectorAll('.lead-row:not([style*="display: none"])');
        const count = rows.length;
        
        const resultsCount = document.getElementById('resultsCount');
        if (resultsCount) {
            resultsCount.textContent = `${count} results`;
        }
        
        // Show "No leads found" message if no rows left
        if (count === 0) {
            const noDataRow = document.createElement('tr');
            noDataRow.innerHTML = '<td colspan="9" style="text-align:center;">No leads found</td>';
            tbody.appendChild(noDataRow);
        }
    }
    
    function showStatusMessage(status) {
        // Create status message element
        const messageDiv = document.createElement('div');
        messageDiv.textContent = status;
        messageDiv.style.cssText = `
            position: fixed;
            top: 18%;
            left: 50%;
            margin-left:35%;
            transform: translate(-50%, -50%);
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        `;
        
        document.body.appendChild(messageDiv);
        
        // Remove after 2 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 2000);
    }
});
</script>

<style>
.upload-form select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.upload-form input[type="file"] {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #fff;
}

.upload-btn {
    background: #2eacb3;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.2s;
}

.upload-btn:hover:not(:disabled) {
    background: #0056b3;
}

.upload-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.btn-loading {
    color: #fff;
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

/* ================= BUTTONS ================= */
.view-btn {
    background: #2eacb3;
    color: #fff;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
}

.view-btn:hover {
    background:rgb(91, 134, 136);
    color: #fff;
}

.location-btn {
    background: #28a745;
    color: #fff;
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.location-btn:hover {
    background: #218838;
    color: #fff;
}

.whatsapp-btn {
    background: #25d366;
    color: #fff;
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 16px;
}

.whatsapp-btn:hover {
    background: #128c7e;
    color: #fff;
}

/* ================= PAGINATION ================= */
.pagination-container {
    margin-top: 25px;
    display: flex;
    justify-content: center;
}

.pagination {
    gap: 6px;
}

.pagination .page-item .page-link {
    border-radius: 8px;
    border: 1px solid #ddd;
    color: #2eacb3;
    padding: 6px 12px;
    font-size: 13px;
    transition: 0.2s ease;
}

.pagination .page-item .page-link:hover {
    background: #2eacb3;
    color: #fff;
    border-color: #2eacb3;
}

.pagination .page-item.active .page-link {
    background: #2eacb3;
    border-color: #2eacb3;
    color: #fff;
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    color: #aaa;
    background: #f8f9fa;
    border-color: #ddd;
}

/* ===== SEARCH CONTAINER ===== */
.search-container{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 10px;
}

/* ===== SEARCH BOX ===== */
.search-box{
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid #dcdfe6;
    border-radius: 10px;
    padding: 4px 6px;
    min-width: 360px;
    transition: all 0.25s ease;
}

/* Focus Effect */
.search-box:focus-within{
    border-color: #2eacb3;
    box-shadow: 0 4px 14px rgba(13,110,253,0.15);
}

/* Search Icon */
.search-box i{
    color: #6c757d;
    font-size: 14px;
    padding: 0 8px;
}

/* Input Field */
.search-box input{
    border: none;
    outline: none;
    font-size: 14px;
    padding: 8px;
    width: 230px;
    background: transparent;
}

/* ===== SEARCH BUTTON ===== */
.search-btn{
    background: #2eacb3;
    color: #fff;
    border: none;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.25s;
}

.search-btn:hover{
    background: #0b5ed7;
    box-shadow: 0 4px 10px rgba(13,110,253,0.25);
}

/* ===== CLEAR BUTTON ===== */
.clear-btn{
    text-decoration: none;
    font-size: 13px;
    padding: 7px 10px;
    border-radius: 6px;
    color: #dc3545;
    background: #fff1f1;
    border: 1px solid #f5c2c7;
    transition: 0.25s;
}

.clear-btn:hover{
    background: #dc3545;
    color: #fff;
}

/* ===== RESULT COUNT ===== */
.results-info{
    font-size: 13px;
    color: #6c757d;
    font-weight: 500;
    background: #f8f9fa;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

/* ===== RESPONSIVE ===== */
@media (max-width:768px){

    .search-container{
        flex-direction: column;
        align-items: flex-start;
    }

    .search-box{
        width: 100%;
        min-width: auto;
    }

    .search-box input{
        width: 100%;
    }
}

/* ===== STATUS TOAST ===== */
.status-toast-container {
    position: fixed;
    top: 20px;
    right: 25px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.status-toast {
    min-width: 260px;
    padding: 14px 18px;
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    box-shadow: 0 6px 18px rgba(0,0,0,0.2);
    animation: slideIn 0.35s ease, fadeOut 0.4s ease 2.6s forwards;
}

/* Different colors per status */
.toast-success { background: #28a745; }
.toast-warning { background: #ffc107; color:#000; }
.toast-danger  { background: #dc3545; }
.toast-info    { background: #17a2b8; }

/* Animations */
@keyframes slideIn {
    from { transform: translateX(120%); opacity:0; }
    to { transform: translateX(0); opacity:1; }
}

@keyframes fadeOut {
    to { opacity:0; transform: translateX(120%); }
}

/* Hidden rows for search */
.lead-row.hidden {
    display: none;
}

/* Search loading state */
.search-loading {
    opacity: 0.6;
    pointer-events: none;
}


</style>

@endsection
