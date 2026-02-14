@extends('auth.layouts.app')
<style>
    /* ===============================
   STATUS DROPDOWN DESIGN
================================ */

.callback-status-select {
    padding: 6px 30px 6px 12px;
    font-size: 12px;
    border-radius: 20px;
    border: 1px solid #ddd;
    outline: none;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    font-weight: 500;
    min-width: 140px;
    transition: all 0.3s ease;
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 12px;
}

/* Dropdown Arrow */
.callback-status-select {
    background-image: url("data:image/svg+xml;utf8,<svg fill='%23666' height='20' viewBox='0 0 20 20' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M5 7l5 5 5-5H5z'/></svg>");
}

/* STATUS COLORS */

.callback-status-select[data-status="call_backs"],
.callback-status-select option[value="call_backs"] {
    background-color: #fff3cd;
    color: #856404;
}

.callback-status-select[data-status="interested"] {
    background-color: #d4edda;
    color: #155724;
}

.callback-status-select[data-status="rejected"] {
    background-color: #f8d7da;
    color: #721c24;
}

.callback-status-select[data-status="not_interested"] {
    background-color: #e2e3e5;
    color: #383d41;
}

.callback-status-select[data-status="wrong_number"] {
    background-color: #cce5ff;
    color: #004085;
}

.callback-status-select:focus {
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

/* ===============================
   MODERN SEARCH DESIGN
================================ */

/* ===============================
   PREMIUM HRMS SEARCH BAR
================================ */

.search-form {
    flex: 1;
    max-width: 500px;
}

.search-box {
    position: relative;
    width: 80%;
}

.search-box input {
    width: 80%;
    height: 42px;
    padding: 0 45px 0 40px;
    border-radius: 30px !important; /* Full round */
    border: 1px solid #e0e6ed !important;
    background: #f8fafc;
    font-size: 14px;
    transition: all 0.3s ease;
}


/* Focus Effect */
.search-box input:focus {
    background: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 4px 12px rgba(79,70,229,0.15);
    outline: none;
}

/* Search Icon */
.search-box i.fa-search {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
}

/* Clear Button */
.clear-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #eef2ff;
    color: #4f46e5;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 12px;
    transition: all 0.2s ease;
}

.clear-btn:hover {
    background: #4f46e5;
    color: #fff;
}


</style>
@section('content')
<div class="main-content">

    <div class="card leads-card">
        <div class="card-header">
            <h4>Callback Leads</h4>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Leads
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Search Bar -->
            <div class="search-container">
            <form method="GET" action="{{ route('admin.callbacks.index') }}" class="search-form" id="searchForm">
    <div class="search-box">
        <i class="fa-solid fa-search"></i>
        <input type="text" 
               name="search" 
               id="searchInput" 
               placeholder="Search by name, number, role, or notes..." 
               value="{{ request('search') }}" 
               autocomplete="off">

        @if(request('search'))
            <a href="{{ route('admin.callbacks.index') }}" class="clear-btn">
                <i class="fa-solid fa-xmark"></i>
            </a>
        @endif
    </div>
</form>

                <div class="results-info">
                    <span id="resultsCount">{{ $callbacks->total() }} results</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Role</th>
                            <th>Callback Date</th>
                            <th>Status</th>
                            <th>Reason/Notes</th>
                            <th>WhatsApp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">
                        @forelse($callbacks->items() as $callback)
                        <tr class="callback-row" data-name="{{ strtolower($callback->name) }}" data-number="{{ $callback->number }}" data-role="{{ strtolower($callback->role) }}" data-notes="{{ strtolower($callback->notes) }}">
                            <td>{{ $callback->name }}</td>
                            <td>{{ $callback->number }}</td>
                            <td>{{ $callback->role }}</td>
                            <td>
                                <input type="date" 
                                       class="form-control callback-date" 
                                       data-id="{{ $callback->id }}"
                                       value="{{ $callback->callback_date ? $callback->callback_date->format('Y-m-d') : '' }}">
                            </td>
                            <td>
                            <select class="callback-status-select" data-id="{{ $callback->id }}">

    <option value="call_backs" {{ $callback->status == 'call_backs' ? 'selected' : '' }}>Call Backs</option>
        <option value="rejected" {{ $callback->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
        <option value="not_interested" {{ $callback->status == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
        <option value="wrong_number" {{ $callback->status == 'wrong_number' ? 'selected' : '' }}>Wrong Number</option>
        <option value="interested" {{ $callback->status == 'interested' ? 'selected' : '' }}>Interested</option>
        
    </select>
</td>

                            <td>
                                <textarea class="form-control callback-notes" 
                                          data-id="{{ $callback->id }}"
                                          rows="2" 
                                          placeholder="Add notes...">{{ $callback->notes }}</textarea>
                            </td>
                            <td>
                                <a href="https://wa.me/91{{ $callback->number }}" target="_blank" class="whatsapp-btn">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success save-callback" data-id="{{ $callback->id }}">
                                    Save
                                </button>
                                <!-- <button class="btn btn-sm btn-danger delete-callback" data-id="{{ $callback->id }}">
                                    Delete
                                </button> -->
                            </td>
                        </tr>
                        @empty
                        <tr id="noResults">
                            <td colspan="7" style="text-align:center;">No callback leads found</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            
            <!-- Pagination -->
            @if($callbacks->hasPages())
            <div class="pagination-container">
                {{ $callbacks->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('tableBody');
    const resultsCount = document.getElementById('resultsCount');
    const noResults = document.getElementById('noResults');
    
    let searchTimeout;
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll('.callback-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const name = row.dataset.name || '';
                const number = row.dataset.number || '';
                const role = row.dataset.role || '';
                const notes = row.dataset.notes || '';
                
                const isMatch = name.includes(searchTerm) || 
                               number.includes(searchTerm) || 
                               role.includes(searchTerm) || 
                               notes.includes(searchTerm);
                
                if (isMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            resultsCount.textContent = `${visibleCount} results`;
            
            if (visibleCount === 0 && searchTerm) {
                if (!document.querySelector('#noSearchResults')) {
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'noSearchResults';
                    noResultsRow.innerHTML = '<td colspan="7" style="text-align:center;">No results found for your search</td>';
                    tableBody.appendChild(noResultsRow);
                }
            } else {
                const noSearchResults = document.querySelector('#noSearchResults');
                if (noSearchResults) {
                    noSearchResults.remove();
                }
            }
        }, 300);
    });

    // Status change functionality
    document.querySelectorAll('.callback-status').forEach(select => {
        select.addEventListener('change', function() {
            const callbackId = this.dataset.id;
            const newStatus = this.value;
            const row = this.closest('tr');
            const notesTextarea = row.querySelector('.callback-notes');
            let reason = notesTextarea ? notesTextarea.value.trim() : '';
            
            // If status changed from call_backs to something else, remove from this page
            if (newStatus !== 'call_backs') {
                // Prompt for reason if not already provided
                if (!reason) {
                    reason = prompt(`Please provide a reason for marking this as ${this.options[this.selectedIndex].text}:`);
                    if (!reason) {
                        // If user cancels or provides empty reason, revert the selection
                        this.value = 'call_backs';
                        return;
                    }
                    // Update the textarea with the new reason
                    if (notesTextarea) {
                        notesTextarea.value = reason;
                    }
                }
                
                fetch(`/admin/callbacks/${callbackId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        status: newStatus,
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove row with animation
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            // Update results count
                            const remainingRows = tableBody.querySelectorAll('.callback-row').length;
                            resultsCount.textContent = `${remainingRows} results`;
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert select to original value on error
                    this.value = 'call_backs';
                });
            }
        });
    });
});
</script>

<style>
    /* ===============================
   SEARCH
================================ */
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

/* ===============================
   PAGINATION
================================ */
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

/* ===============================
   GLOBAL RESET (SAFE)
================================ */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f4f6f9;
    overflow-x: hidden;
}
.main-content{
   
    margin-top: 60px;
}

/* ===============================
   LAYOUT FIX (SIDEBAR + CONTENT)
================================ */
/* sidebar width = 250px assumed */

.content,
.content-wrapper,
.page-content,
.container-fluid {
    margin-left: 250px !important;
    width: calc(100vw - 250px) !important;
    max-width: calc(100vw - 250px) !important;
    padding: 0 !important;
}

/* ===============================
   PAGE CONTENT
================================ */


/* ===============================
   CARD
================================ */
.leads-card {
    width: 100%;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    overflow: hidden;
}

/* HEADER */
.leads-card .card-header {
 
    background: #ffffff;
    border-bottom: 1px solid #e6e6e6;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.leads-card .card-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

/* ===============================
   TABLE
================================ */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.leads-table {
    width: 100%;
   
    border-collapse: collapse;
}

.leads-table thead th {
    background: #f1f3f5;
    padding: 14px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #444;
    text-align: left;
    white-space: nowrap;
}

.leads-table tbody td {
    padding: 14px 12px;
    font-size: 13px;
    color: #333;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.leads-table tbody tr:hover {
    background: #fafafa;
}

/* ===============================
   INPUTS
================================ */
.form-control {
    font-size: 12px;
    padding: 6px 8px;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.callback-notes {
    min-width: 200px;
    resize: vertical;
}

/* ===============================
   WHATSAPP BUTTON
================================ */
.whatsapp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #25d366;
    color: #fff;
    border-radius: 50%;
    text-decoration: none;
    font-size: 16px;
    transition: all 0.2s;
}

.whatsapp-btn:hover {
    background: #128c7e;
    color: #fff;
    transform: scale(1.1);
}

.callback-row.hidden {
    display: none;
}

.search-loading {
    opacity: 0.6;
    pointer-events: none;
}{
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #25D366;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    text-decoration: none;
}

.whatsapp-btn:hover {
    background: #1ebe5d;
    color: #fff;
}

/* ===============================
   ACTION BUTTONS
================================ */
.leads-table td:last-child {
    white-space: nowrap;
}

.btn {
    font-size: 12px;
    padding: 6px 10px;
    border-radius: 6px;
    border: none;
}

.btn-success {
    background: #28a745;
    color: #fff;
}

.btn-danger {
    background: #dc3545;
    color: #fff;
}

.btn-outline-secondary {
    background: transparent;
    border: 1px solid #6c757d;
    color: #6c757d;
}

.btn + .btn {
    margin-left: 6px;
}

/* ===============================
   ALERT
================================ */
.alert-success {
    font-size: 13px;
    border-radius: 6px;
}

/* ===============================
   MOBILE VIEW
================================ */
@media (max-width: 992px) {

    .content,
    .content-wrapper,
    .page-content,
    .container-fluid {
        margin-left: 0 !important;
        width: 100vw !important;
        max-width: 100vw !important;
    }

    .main-content {
        padding: 16px;
    }

    .leads-table {
        min-width: 100%;
    }

    .leads-table thead {
        display: none;
    }

    .leads-table tr {
        display: block;
        margin-bottom: 14px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.06);
        padding: 10px;
    }

    .leads-table td {
        display: flex;
        justify-content: space-between;
        padding: 8px 6px;
        border-bottom: none;
        font-size: 13px;
    }

    .leads-table td:last-child {
        justify-content: flex-start;
        gap: 8px;
    }
    
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('tableBody');
    const resultsCount = document.getElementById('resultsCount');
    const callbackRows = document.querySelectorAll('.callback-row');
    const noResults = document.getElementById('noResults');
    
    // Auto search function
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        callbackRows.forEach(row => {
            const name = row.dataset.name || '';
            const number = row.dataset.number || '';
            const role = row.dataset.role || '';
            const notes = row.dataset.notes || '';
            
            const isMatch = name.includes(searchTerm) || 
                          number.includes(searchTerm) || 
                          role.includes(searchTerm) || 
                          notes.includes(searchTerm);
            
            if (isMatch || searchTerm === '') {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });
        
        // Update results count
        resultsCount.textContent = `${visibleCount} results`;
        
        // Show/hide no results message
        if (noResults) {
            if (visibleCount === 0 && searchTerm !== '') {
                noResults.style.display = 'table-row';
                noResults.innerHTML = '<td colspan="7" style="text-align:center;">No matching results found</td>';
            } else if (callbackRows.length === 0) {
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
    
    // Save callback functionality
    document.querySelectorAll('.save-callback').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const date = document.querySelector(`.callback-date[data-id="${id}"]`).value;
            const notes = document.querySelector(`.callback-notes[data-id="${id}"]`).value;
            
            fetch(`/admin/callbacks/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    callback_date: date,
                    notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Callback updated successfully!');
                }
            });
        });
    });
});
</script>
@endsection