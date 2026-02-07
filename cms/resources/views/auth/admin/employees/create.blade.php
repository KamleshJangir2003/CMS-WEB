@extends('auth.layouts.app')

@section('content')
<style>
    .container-fluid{
    margin-top: 60px !important;
    padding-left: 130px !important;
}
</style>
<div class="container-fluid">

    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h4 class="page-title">Add Employee</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            {{-- Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.employee.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- PERSONAL DETAILS -->
                <h5 class="mb-3">Personal Details</h5>
                <div class="row">
<div class="row">
    <div class="col-md-6 mb-3">
        <label>First Name <span class="text-danger">*</span></label>
        <input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Last Name <span class="text-danger">*</span></label>
        <input type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
    </div>
</div>


                    <div class="col-md-6 mb-3">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Father Name <span class="text-danger">*</span></label>
                        <input type="text" name="father_name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Mother Name <span class="text-danger">*</span></label>
                        <input type="text" name="mother_name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Contact Number <span class="text-danger">*</span></label>
                        <input type="text" name="contact_number" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Guardian Number <span class="text-danger">*</span></label>
                        <input type="text" name="guardian_number" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
    <label>Gender <span class="text-danger">*</span></label>
    <select name="gender" class="form-control" required>
        <option value="">Select Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select>
</div>


                </div>

                <hr>
             

<!-- ADDRESS DETAILS -->
<h5 class="mb-3">Address Details</h5>
<div class="row">

    <div class="col-md-12 mb-3">
        <label>Full Address <span class="text-danger">*</span></label>
        <textarea name="address" class="form-control" rows="3" required></textarea>
    </div>

    <div class="col-md-4 mb-3">
        <label>City <span class="text-danger">*</span></label>
        <input type="text" name="city" class="form-control" required>
    </div>

    <div class="col-md-4 mb-3">
        <label>State <span class="text-danger">*</span></label>
        <input type="text" name="state" class="form-control" required>
    </div>

    <div class="col-md-4 mb-3">
        <label>Pincode <span class="text-danger">*</span></label>
        <input type="text" name="pincode" class="form-control" required>
    </div>

</div>
<hr>

                <!-- PREVIOUS COMPANY DETAILS -->
                <h5 class="mb-3">Previous Employment Details</h5>
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Last Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_company_name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Last Salary (In Hand) <span class="text-danger">*</span></label>
                        <input type="number" name="last_salary_in_hand" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Last Salary (CTC) <span class="text-danger">*</span></label>
                        <input type="number" name="last_salary_ctc" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>UAN Number <span class="text-danger">*</span></label>
                        <input type="text" name="uan_number" class="form-control" required>
                    </div>

                </div>

                <hr>

                <!-- BANK DETAILS -->
                <h5 class="mb-3">Bank Details</h5>
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Bank Name <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>IFSC Code <span class="text-danger">*</span></label>
                        <input type="text" name="ifsc_code" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Bank Account Number <span class="text-danger">*</span></label>
                        <input type="text" name="bank_account_number" class="form-control" required>
                    </div>

                </div>

                <hr>
             

<!-- LOGIN CREDENTIALS -->
<!-- <h5 class="mb-3">Login Credentials</h5>
<div class="row">

    <div class="col-md-6 mb-3">
        <label>Create Password <span class="text-danger">*</span></label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>Re-enter Password <span class="text-danger">*</span></label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

</div>

<hr> -->

<h5 class="mb-3">Job Details</h5>

<div class="row">
    <div class="col-md-6 mb-3">
        <label>Department <span class="text-danger">*</span></label>
        <select name="department" class="form-control" required>
            <option value="">Select Department</option>
            <option value="HR">HR</option>
            <option value="IT">IT</option>
            <option value="Sales">Sales</option>
            <option value="Accounts">Accounts</option>
        </select>
    </div>
</div>
<hr>
                <!-- SELFIE -->
                <h5 class="mb-3">Employee Selfie</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Upload Selfie <span class="text-danger">*</span></label>
                        <input type="file" name="selfie" class="form-control" required>
                    </div>
                </div>

                <!-- BUTTONS -->
               <div class="mt-4">
    <button type="submit" class="btn btn-primary">
        Save Employee
    </button>

    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
        Cancel
    </a>
</div>


            </form>

        </div>
    </div>

</div>
@endsection
