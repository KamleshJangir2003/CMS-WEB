<!-- resources/views/employee/dashboard.blade.php -->
@extends('auth.layouts.app')


@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Employee Dashboard</h1>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Welcome, {{ $full_name }}!</h5>
            <p class="card-text">You are logged in as an Employee.</p>
            
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6>Your Information:</h6>
                            <p><strong>Department:</strong> {{ $user->department }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h6>Quick Actions:</h6>
                            <a href="{{ route('employee.profile') }}" class="btn btn-primary">View Profile</a>
                            <a href="{{ route('employee.tasks') }}" class="btn btn-success">View Tasks</a>
                            <a href="{{ route('employee.reports') }}" class="btn btn-info">View Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection