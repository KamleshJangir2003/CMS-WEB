@extends('layouts.app')

@section('content')

<style>
/* ================= Salary Calculator FIXED CSS ================= */

/* Actual calculator page layout */
.salary-calculator-page {
    margin-left: 130px; /* sidebar width */
    margin-top: 80px; /* header height + padding */
    padding: 20px;
}

/* Card */
.salary-calculator-page .card {
    border-radius: 12px;
    border: none;
}

/* Header */
.salary-calculator-page .card-header {
    background: linear-gradient(135deg, #2eacb3, #084298);
    border-radius: 12px 12px 0 0;
    padding: 14px 18px;
}

.salary-calculator-page .card-header h5 {
    font-size: 18px;
    font-weight: 600;
    color: white;
}

/* Form */
.salary-calculator-page .form-label {
    font-weight: 600;
    font-size: 14px;
}

.salary-calculator-page .form-control {
    height: 44px;
    border-radius: 8px;
}

/* Button */
.salary-calculator-page .btn-success {
    height: 45px;
    border-radius: 8px;
    font-weight: 600;
}

/* Table */
.salary-calculator-page table {
    font-size: 14px;
}

.salary-calculator-page table th {
    background: #f8f9fa;
    width: 55%;
    font-weight: 600;
}

.salary-calculator-page table td {
    font-weight: 500;
}

/* Highlights */
.salary-calculator-page .table-success th,
.salary-calculator-page .table-success td {
    background: #e6f4ea !important;
    color: #146c43;
}

.salary-calculator-page .table-info th,
.salary-calculator-page .table-info td {
    background: #e7f1ff !important;
    color: #084298;
}

/* Mobile Fix */
@media (max-width: 768px) {
    .salary-calculator-page {
        margin-left: 0;
        margin-top: 15px;
        padding: 15px;
    }
}
</style>

<div class="container salary-calculator-page">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Salary Calculator</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('salary.calculate') }}" id="salaryForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">In-Hand Salary</label>
                        <input type="number"
                               name="in_hand_salary"
                               class="form-control"
                               value="{{ old('in_hand_salary', $in_hand ?? '') }}"
                               placeholder="Enter in-hand salary"
                               required>
                    </div>

                    <button class="btn btn-success w-100">
                        Calculate Gross & CTC
                    </button>
                </form>

                @isset($in_hand)
                <hr>

                <table class="table table-bordered mt-3">
                    <tr>
                        <th>Gross Salary</th>
                        <td>₹ {{ number_format($gross, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Basic Salary (60%)</th>
                        <td>₹ {{ number_format($basic, 2) }}</td>
                    </tr>
                    <tr>
                        <th>HRA (40%)</th>
                        <td>₹ {{ number_format($hra, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Employee PF (12% of Basic)</th>
                        <td>₹ {{ number_format($employee_pf, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Employee ESIC (0.75% of Gross)</th>
                        <td>₹ {{ number_format($employee_esic, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Employer PF (13% of Basic)</th>
                        <td>₹ {{ number_format($employer_pf, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Employer ESIC (3.25% of Gross)</th>
                        <td>₹ {{ number_format($employer_esic, 2) }}</td>
                    </tr>
                    <tr class="table-success">
                        <th>In-Hand Salary</th>
                        <td><strong>₹ {{ number_format($in_hand, 2) }}</strong></td>
                    </tr>
                    <tr class="table-info">
                        <th>Total CTC</th>
                        <td><strong>₹ {{ number_format($ctc, 2) }}</strong></td>
                    </tr>
                </table>
                @endisset
            </div>
        </div>
</div>



@endsection
