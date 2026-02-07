@extends('auth.layouts.app')

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
        <p>View and manage all employee profiles</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="card-header">
            <h3>All Employee Profiles</h3>
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
</style>
@endsection