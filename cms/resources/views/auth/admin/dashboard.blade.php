<!-- resources/views/admin/dashboard.blade.php -->
@extends('auth.layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


@section('content')
<div class="container-fluid">
   
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Employees</h5>
                    <h2>{{ $stats['totalEmployees'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Approvals</h5>
                    <h2>{{ $stats['pendingApprovals'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Admins</h5>
                    <h2>{{ $stats['totalAdmins'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Clients</h5>
                    <h2>{{ $stats['totalClients'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($pendingUsers) && $pendingUsers->count() > 0)
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Pending Approvals</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                    <tr>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->department }}</td>
                        <td>{{ ucfirst($user->user_type) }}</td>
                        <td>
                            <form action="{{ route('admin.approve', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Welcome, {{ Auth::user()->first_name }}!</h5>
            <p class="card-text">You are logged in as an Administrator.</p>
            <a href="{{ route('admin.users') }}" class="btn btn-primary">Manage Users</a>
        </div>
    </div>
</div>
@endsection