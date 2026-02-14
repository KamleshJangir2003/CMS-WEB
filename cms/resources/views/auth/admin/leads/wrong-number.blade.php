@extends('auth.layouts.app')

@section('content')
<div class="main-content">
    <div class="card leads-card">
        <div class="card-header">
            <h4>Wrong Number Leads</h4>
        </div>

        <div class="card-body">
            <!-- Search Bar -->
            <div class="search-container">
                <form method="GET" action="{{ route('admin.leads.wrong-number') }}" class="search-form" id="searchForm">
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" name="search" id="searchInput" placeholder="Search by name, number, or role..." value="{{ request('search') }}" autocomplete="off">
                        <button type="submit" class="search-btn">Search</button>
                        @if(request('search'))
                            <a href="{{ route('admin.leads.wrong-number') }}" class="clear-btn">Clear</a>
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
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($leads->items() as $lead)
                        <tr class="lead-row" data-name="{{ strtolower($lead->name) }}" data-number="{{ $lead->number }}" data-role="{{ strtolower($lead->role) }}">
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
                            <td><span class="badge badge-warning">Wrong Number</span></td>
                            <td>{{ $lead->updated_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="https://wa.me/91{{ $lead->number }}" target="_blank" class="whatsapp-btn">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr id="noResults">
                            <td colspan="6" style="text-align:center;">No wrong number leads found</td>
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
}

.card-header h4 {
    margin: 0;
    font-weight: 600;
}

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

.search-btn {
    padding: 10px 16px;
    background: #007bff;
    color: #fff;
    border: 1px solid #007bff;
    border-radius: 0;
    font-size: 14px;
    cursor: pointer;
}

.clear-btn {
    padding: 10px 12px;
    background: #6c757d;
    color: #fff;
    text-decoration: none;
    border-radius: 0 8px 8px 0;
    font-size: 14px;
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

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
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

.pagination-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('tableBody');
    const resultsCount = document.getElementById('resultsCount');
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = tableBody.querySelectorAll('.lead-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const name = row.dataset.name || '';
                const number = row.dataset.number || '';
                const role = row.dataset.role || '';
                
                const isMatch = name.includes(searchTerm) || 
                               number.includes(searchTerm) || 
                               role.includes(searchTerm);
                
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
                    noResultsRow.innerHTML = '<td colspan="6" style="text-align:center;">No results found for your search</td>';
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
});
</script>
@endsection