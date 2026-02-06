@extends('auth.layouts.app')

@section('content')
<div class="container-fluid">

<h4 class="mb-4">Edit Employee</h4>

<div class="card">
<div class="card-body">

<form method="POST"
      action="{{ route('admin.employees.update', $employee->id) }}"
      enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- ================= PERSONAL DETAILS ================= --}}
<h5 class="mb-3">Personal Details</h5>
<div class="row">

<div class="col-md-6 mb-3">
<label>First Name</label>
<input type="text" name="first_name" class="form-control"
value="{{ old('first_name',$employee->first_name) }}">
</div>

<div class="col-md-6 mb-3">
<label>Last Name</label>
<input type="text" name="last_name" class="form-control"
value="{{ old('last_name',$employee->last_name) }}">
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control"
value="{{ old('email',$employee->email) }}">
</div>

<div class="col-md-6 mb-3">
<label>Father Name</label>
<input type="text" name="father_name" class="form-control"
value="{{ old('father_name',$employee->father_name) }}">
</div>

<div class="col-md-6 mb-3">
<label>Mother Name</label>
<input type="text" name="mother_name" class="form-control"
value="{{ old('mother_name',$employee->mother_name) }}">
</div>

<div class="col-md-6 mb-3">
<label>Date of Birth</label>
<input type="date" name="dob" class="form-control"
value="{{ old('dob',$employee->dob) }}">
</div>

<div class="col-md-6 mb-3">
<label>Contact Number</label>
<input type="text" name="contact_number" class="form-control"
value="{{ old('contact_number',$employee->contact_number) }}">
</div>

<div class="col-md-6 mb-3">
<label>Guardian Number</label>
<input type="text" name="guardian_number" class="form-control"
value="{{ old('guardian_number',$employee->guardian_number) }}">
</div>

<div class="col-md-6 mb-3">
<label>Gender</label>
<select name="gender" class="form-control">
<option value="">Select</option>
<option value="male" {{ $employee->gender=='male'?'selected':'' }}>Male</option>
<option value="female" {{ $employee->gender=='female'?'selected':'' }}>Female</option>
<option value="other" {{ $employee->gender=='other'?'selected':'' }}>Other</option>
</select>
</div>

</div>

<hr>

{{-- ================= ADDRESS DETAILS ================= --}}
<h5 class="mb-3">Address Details</h5>
<div class="row">

<div class="col-md-12 mb-3">
<label>Address</label>
<textarea name="address" class="form-control">{{ old('address',$employee->address) }}</textarea>
</div>

<div class="col-md-4 mb-3">
<label>City</label>
<input type="text" name="city" class="form-control"
value="{{ old('city',$employee->city) }}">
</div>

<div class="col-md-4 mb-3">
<label>State</label>
<input type="text" name="state" class="form-control"
value="{{ old('state',$employee->state) }}">
</div>

<div class="col-md-4 mb-3">
<label>Pincode</label>
<input type="text" name="pincode" class="form-control"
value="{{ old('pincode',$employee->pincode) }}">
</div>

</div>

<hr>

{{-- ================= PREVIOUS EMPLOYMENT ================= --}}
<h5 class="mb-3">Previous Employment Details</h5>
<div class="row">

<div class="col-md-6 mb-3">
<label>Last Company Name</label>
<input type="text" name="last_company_name" class="form-control"
value="{{ old('last_company_name',$employee->last_company_name) }}">
</div>

<div class="col-md-6 mb-3">
<label>Last Salary (In Hand)</label>
<input type="number" name="last_salary_in_hand" class="form-control"
value="{{ old('last_salary_in_hand',$employee->last_salary_in_hand) }}">
</div>

<div class="col-md-6 mb-3">
<label>Last Salary (CTC)</label>
<input type="number" name="last_salary_ctc" class="form-control"
value="{{ old('last_salary_ctc',$employee->last_salary_ctc) }}">
</div>

<div class="col-md-6 mb-3">
<label>UAN Number</label>
<input type="text" name="uan_number" class="form-control"
value="{{ old('uan_number',$employee->uan_number) }}">
</div>

</div>

<hr>

{{-- ================= BANK DETAILS ================= --}}
<h5 class="mb-3">Bank Details</h5>
<div class="row">

<div class="col-md-6 mb-3">
<label>Bank Name</label>
<input type="text" name="bank_name" class="form-control"
value="{{ old('bank_name',$employee->bank_name) }}">
</div>

<div class="col-md-6 mb-3">
<label>IFSC Code</label>
<input type="text" name="ifsc_code" class="form-control"
value="{{ old('ifsc_code',$employee->ifsc_code) }}">
</div>

<div class="col-md-6 mb-3">
<label>Account Number</label>
<input type="text" name="bank_account_number" class="form-control"
value="{{ old('bank_account_number',$employee->bank_account_number) }}">
</div>

</div>

<hr>

{{-- ================= JOB DETAILS ================= --}}
<h5 class="mb-3">Job Details</h5>
<div class="col-md-6 mb-3">
<label>Department</label>
<select name="department" class="form-control">
<option value="HR" {{ $employee->department=='HR'?'selected':'' }}>HR</option>
<option value="IT" {{ $employee->department=='IT'?'selected':'' }}>IT</option>
<option value="Sales" {{ $employee->department=='Sales'?'selected':'' }}>Sales</option>
<option value="Accounts" {{ $employee->department=='Accounts'?'selected':'' }}>Accounts</option>
</select>
</div>

<hr>

{{-- ================= SELFIE ================= --}}
<h5 class="mb-3">Employee Selfie</h5>
<input type="file" name="selfie" class="form-control">
@if($employee->selfie)
<small>Current: {{ $employee->selfie }}</small>
@endif

<div class="mt-4">
<button class="btn btn-primary">Update Employee</button>
<a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>
</div>
</div>
</div>
@endsection
