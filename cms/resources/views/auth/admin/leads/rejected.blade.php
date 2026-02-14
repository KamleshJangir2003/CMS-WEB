@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="card leads-card">
        <div class="card-header">
            <h4>Rejected Leads</h4>
            <div class="search-container">
                <form method="GET" action="{{ route('admin.leads.rejected') }}" class="search-form" id="searchForm">
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" name="search" id="searchInput" placeholder="Search by name, number, role, status, or reason..." value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="search-btn">Search</button>
                        @if(request('search'))
                            <a href="{{ route('admin.leads.rejected') }}" class="clear-btn">Clear</a>
                        @endif
                    </div>
                </form>
                <div class="results-info">
                    <span id="resultsCount">{{ $leads->total() }} results</span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Updated At</th>
                            <th>WhatsApp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($leads as $lead)
                        <tr class="lead-row" data-name="{{ strtolower($lead->platform === 'indeed' ? $lead->number : $lead->name) }}" data-number="{{ $lead->platform === 'indeed' ? str_replace(["'+91 ", " "], ["", ""], $lead->role) : $lead->number }}" data-role="{{ strtolower($lead->platform === 'indeed' ? 'php developer' : $lead->role) }}" data-status="{{ strtolower($lead->condition_status) }}" data-reason="{{ strtolower($lead->reason ?? '') }}">
                            <td>
                                @if($lead->platform === 'indeed')
                                    {{ $lead->number }} {{-- For Indeed leads, name is in number field --}}
                                @else
                                    {{ $lead->name }}
                                @endif
                            </td>
                            <td>
                                @if($lead->platform === 'indeed')
                                    {{ str_replace("'+91 ", "", $lead->role) }} {{-- For Indeed leads, phone is in role field --}}
                                @else
                                    {{ $lead->number }}
                                @endif
                            </td>
                            <td>
                                <div>
                                    @if($lead->platform === 'indeed')
                                        <span class="fw-medium">PHP Developer</span> {{-- Default role for Indeed leads --}}
                                    @else
                                        <span class="fw-medium">{{ $lead->role }}</span>
                                    @endif
                                    @if($lead->platform)
                                        <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $lead->platform)) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-danger">{{ $lead->condition_status }}</span>
                            </td>
                            <td>{{ $lead->reason ?? 'No reason provided' }}</td>
                            <td>{{ $lead->updated_at->format('d M Y, h:i A') }}</td>
                            <td>
                                @php
                                    $phoneNumber = $lead->platform === 'indeed' 
                                        ? str_replace(["'+91 ", " "], ["", ""], $lead->role)
                                        : $lead->number;
                                @endphp
                                <a href="https://wa.me/91{{ $phoneNumber }}" target="_blank" class="whatsapp-btn">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.leads.cv', $lead->id) }}" class="view-btn">View CV</a>
                            </td>
                        </tr>
                        @empty
                        <tr id="noResults">
                            <td colspan="8" class="text-center">No rejected leads found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leads->hasPages())
            <div class="pagination-container">
                {{ $leads->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* ================= MAIN CONTENT ================= */
.main-content {
    margin-left: 130px;

    min-height: 100vh;
    background: #f8f9fa;
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
    flex-direction: column;
    gap: 15px;
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
    align-self: flex-start;
}

.search-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 15px;
}

/* ================= SEARCH ================= */
.search-form {
    flex: 1;
    max-width: 500px;
    display: flex;
    align-items: center;
    gap: 0;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0;
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
    padding: 8px 12px 8px 35px;
    border: 1px solid #ddd;
    border-radius: 6px 0 0 6px;
    font-size: 14px;
    background: #fff;
    border-right: none;
}

.search-btn {
    padding: 8px 16px;
    background: #007bff;
    color: #fff;
    border: 1px solid #007bff;
    border-radius: 0;
    font-size: 14px;
    cursor: pointer;
}

.clear-btn {
    padding: 8px 12px;
    background: #6c757d;
    color: #fff;
    text-decoration: none;
    border-radius: 0 6px 6px 0;
    font-size: 14px;
}

.results-info {
    font-size: 14px;
    color: #666;
    font-weight: 500;
    background: #f8f9fa;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
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

.text-center {
    text-align: center;
}

/* ================= BADGES ================= */
.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

/* ================= BUTTONS ================= */
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
    color: #fff;
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
    color: #fff;
}

/* ================= PAGINATION ================= */
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

/* ================= MOBILE ================= */
@media (max-width: 992px) {
    .main-content {
        margin-left: 0;
        width: 100%;
    }
    
    .card-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .search-form {
        max-width: 100%;
    }
}
</style>

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
            const status = row.dataset.status || '';
            const reason = row.dataset.reason || '';
            
            const isMatch = name.includes(searchTerm) || 
                          number.includes(searchTerm) || 
                          role.includes(searchTerm) ||
                          status.includes(searchTerm) ||
                          reason.includes(searchTerm);
            
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
                noResults.innerHTML = '<td colspan="8" class="text-center">No matching results found</td>';
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
});
</script>

@endsection