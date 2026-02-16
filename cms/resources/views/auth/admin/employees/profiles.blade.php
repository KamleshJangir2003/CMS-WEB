@extends('auth.layouts.app')
<style>
   .search-container {
    display: flex;
    align-items: center;
    margin-top: 20px;
}

#searchForm {
    position: relative;
}

.search-input {
    width: 350px;
    height: 42px;
    padding: 0 18px 0 45px;
    border-radius: 30px;
    border: 1px solid #e5e7eb;
    background-color: #f3f4f6;
    font-size: 14px;
    transition: all 0.3s ease;
    outline: none;
}

/* Icon inside input */
#searchForm::before {
    content: "\f002";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 14px;
    pointer-events: none;
}

/* Hover */
.search-input:hover {
    background-color: #ffffff;
}

/* Focus */
.search-input:focus {
    background-color: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}


</style>
@section('title', 'Employee Profiles')
<style>
    .container-fluid{
    margin-top: 60px !important;
    padding-left: 130px !important;
}
</style>
@section('content')
<div class="main-content">
    <div class="page-header">
        <h1>Employee Profiles</h1>
        <!-- <p>View and manage all employee profiles</p> -->
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="card-header">
            
            <div class="search-container">
                <form method="GET" action="{{ route('admin.employees.profiles') }}" id="searchForm">
                    <input type="text" name="search" placeholder="Search employees..." 
                           value="{{ request('search') }}" class="search-input" id="searchInput">
                </form>
            </div>
            <div class="total-count">
               
                    @if(isset($employees) && method_exists($employees, 'total'))
                        ({{ $employees->total() }} total)
                    @elseif(isset($employees))
                        ({{ $employees->count() }} total)
                    @endif
                
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>
                                @if($employee->selfie)
                                    <img src="{{ asset('storage/' . $employee->selfie) }}" 
                                         alt="Profile" class="profile-img">
                                @else
                                    <div class="no-photo">No Photo</div>
                                @endif
                            </td>
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->contact_number ?? $employee->phone }}</td>
                            <td>
                                <a href="{{ route('admin.employees.profile.show', $employee->id) }}" 
                                   class="btn btn-primary btn-sm">View Profile</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($employees) && method_exists($employees, 'total'))
            <div class="pagination-info">
                <div class="pagination-text">
                    <p>Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} total employees</p>
                </div>
                <div class="pagination-links">
                    {{ $employees->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.profile-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.no-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: #666;
}

.table th, .table td {
    vertical-align: middle;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}



.pagination-info {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-text p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.pagination-links {
    display: flex;
    align-items: center;
}

/* Hide default Laravel pagination text */
.pagination-links .hidden {
    display: none !important;
}

.pagination-links p {
    display: none !important;
}

/* Style pagination buttons */
.pagination-links nav {
    display: flex;
    align-items: center;
}

.pagination-links .pagination {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.pagination-links .page-link {
    padding: 8px 12px;
    border: 1px solid #ddd;
    color: #2eacb3;
    text-decoration: none;
    border-radius: 4px;
}

.pagination-links .page-link:hover {
    background: #f8f9fa;
}

.pagination-links .page-item.active .page-link {
    background: #2eacb3;
    color: white;
    border-color: #2eacb3;
}
</style>

<script>
document.getElementById('searchInput').addEventListener('input', function() {
    document.getElementById('searchForm').submit();
});
</script>
@endsection