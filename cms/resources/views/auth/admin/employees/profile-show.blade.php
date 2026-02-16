@extends('auth.layouts.app')

@section('title', 'Employee Profile - ' . $employee->first_name . ' ' . $employee->last_name)
<style>
    .container-fluid{
    margin-top: 60px !important;
    padding-left: 130px !important;
}
</style>
@section('content')
<div class="main-content">
    <div class="page-header">
        <h1>Employee Profile</h1>
        
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-photo">
                @if($employee->selfie)
                    <img src="{{ asset('storage/' . $employee->selfie) }}" alt="Profile Photo">
                @else
                    <div class="no-photo-large">
                        <i class="fa-solid fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="profile-info">
                <h2>{{ $employee->first_name }} {{ $employee->last_name }}</h2>
                <p>{{ $employee->department }}</p>
                <p>{{ $employee->email }}</p>
            </div>
        </div>

        <!-- Profile Form -->
        <form method="POST" action="{{ route('admin.employees.profile.update', $employee->id) }}">
            @csrf
            @method('PUT')

            <div class="form-sections">
                <!-- Personal Information -->
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>First Name *</label>
                            <input type="text" name="first_name" value="{{ $employee->first_name }}" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name *</label>
                            <input type="text" name="last_name" value="{{ $employee->last_name }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" value="{{ $employee->email }}" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Number *</label>
                            <input type="text" name="contact_number" value="{{ $employee->contact_number ?? $employee->phone }}" required>
                        </div>
                        <div class="form-group">
                            <label>Father's Name</label>
                            <input type="text" name="father_name" value="{{ $employee->father_name }}">
                        </div>
                        <div class="form-group">
                            <label>Mother's Name</label>
                            <input type="text" name="mother_name" value="{{ $employee->mother_name }}">
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" value="{{ $employee->dob }}">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ $employee->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $employee->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $employee->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Guardian Number</label>
                            <input type="text" name="guardian_number" value="{{ $employee->guardian_number }}">
                        </div>
                        <div class="form-group">
                            <label>Department *</label>
                            <input type="text" name="department" value="{{ $employee->department }}" required>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section">
                    <h3>Address Information</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Address</label>
                            <textarea name="address" rows="3">{{ $employee->address }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" value="{{ $employee->city }}">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" value="{{ $employee->state }}">
                        </div>
                        <div class="form-group">
                            <label>Pincode</label>
                            <input type="text" name="pincode" value="{{ $employee->pincode }}">
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                <div class="form-section">
                    <h3>Bank Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Bank Name</label>
                            <input type="text" name="bank_name" value="{{ $employee->bank_name }}">
                        </div>
                        <div class="form-group">
                            <label>IFSC Code</label>
                            <input type="text" name="ifsc_code" value="{{ $employee->ifsc_code }}">
                        </div>
                        <div class="form-group">
                            <label>Account Number</label>
                            <input type="text" name="bank_account_number" value="{{ $employee->bank_account_number }}">
                        </div>
                        <div class="form-group">
                            <label>Holder Name</label>
                            <input type="text" name="bank_account_number" value="{{ $employee->bank_account_number }}">
                        </div>
                        <div class="form-group">
                            <label>UPI ID</label>
                            <input type="text" name="bank_account_number" value="{{ $employee->bank_account_number }}">
                        </div>
                    </div>
                </div>

                <!-- Previous Employment (Read Only) -->
               
                <div class="form-section">
                    <h3>Previous Employment (Read Only)</h3>
                    <div class="form-grid">
                        <!-- <div class="form-group">
                            <label>Last Company</label>
                            <input type="text" value="{{ $employee->last_company_name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Last Salary (In Hand)</label>
                            <input type="text" value="₹{{ number_format($employee->last_salary_in_hand) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Last Salary (CTC)</label>
                            <input type="text" value="₹{{ number_format($employee->last_salary_ctc) }}" readonly>
                        </div> -->
                        <div class="form-group">
                            <label>UAN Number</label>
                            <input type="text" value="{{ $employee->uan_number }}">
                        </div>
                        <div class="form-group">
                            <label>ESI</label>
                            <input type="text" value="{{ $employee->uan_number }}">
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <h3>Present Salary</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Present Salary (In Hand)</label>
                            <input type="number" name="in_hand_salary" value="{{ $employee->in_hand_salary }}" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Present Salary (CTC)</label>
                            <input type="number" name="current_ctc" value="{{ $employee->current_ctc }}" step="0.01">
                        </div>
                        <div class="form-group">
                            <label>Joining Date</label>
                            <input type="date" name="joining_date" value="{{ $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="{{ route('admin.employees.profiles') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>
</div>

<style>
.profile-container {
    max-width: 1000px;
    margin: 0 auto;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.profile-photo img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.no-photo-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: #666;
}

.profile-info h2 {
    margin: 0 0 5px 0;
    color: #333;
}

.profile-info p {
    margin: 0;
    color: #666;
}

.form-sections {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.form-section {
    padding: 25px;
    border-bottom: 1px solid #eee;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 18px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-group input[readonly] {
    background: #f8f9fa;
    color: #666;
}

.form-actions {
    padding: 25px;
    background: #f8f9fa;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
}

.btn-primary {
    background: #2eacb3;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
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

.alert ul {
    margin: 0;
    padding-left: 20px;
}
</style>
@endsection