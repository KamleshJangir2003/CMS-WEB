@extends('auth.layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h1><i class="fa-solid fa-user"></i> My Profile</h1>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Profile Information -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-user-edit"></i> Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="{{ old('first_name', $user->first_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="{{ old('last_name', $user->last_name) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone', $user->phone) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="department">Department</label>
                                        <input type="text" class="form-control" id="department" name="department" 
                                               value="{{ old('department', $user->department) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="user_type">User Type</label>
                                        <input type="text" class="form-control" value="{{ ucfirst($user->user_type) }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save"></i> Update Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-info-circle"></i> Profile Summary</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="profile-avatar mb-3">
                            <img src="https://i.pravatar.cc/120" class="rounded-circle" alt="Profile" style="width: 120px; height: 120px;">
                        </div>
                        <h4>{{ $user->full_name }}</h4>
                        <p class="text-muted">{{ ucfirst($user->user_type) }}</p>
                        <p class="text-muted">{{ $user->department }}</p>
                        
                        <div class="profile-stats mt-4">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h5>{{ $user->created_at->format('M Y') }}</h5>
                                        <small class="text-muted">Joined</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h5>{{ $user->updated_at->diffForHumans() }}</h5>
                                        <small class="text-muted">Last Updated</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fa-solid fa-lock"></i> Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.change-password') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="new_password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                <i class="fa-solid fa-key"></i> Change Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar img {
    border: 4px solid #f8f9fa;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.stat-item h5 {
    margin-bottom: 5px;
    color: #495057;
}

.card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #2eacb3 0%, #2eacb3 100%);
    color: white;
    border-bottom: none;
}

.card-header h5 {
    margin: 0;
    font-weight: 500;
}

.form-control:focus {
    border-color: #2eacb3;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #2eacb3 0%, #2eacb3 100%);
    border: none;
}

.btn-warning {
    background: linear-gradient(135deg, #2eacb3 0%, #2eacb3 100%);
    border: none;
    color: white;
}
</style>
@endsection