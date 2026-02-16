@extends('auth.layouts.app')
<style>
    /* ===== FIX CONTENT SHIFT WITH SIDEBAR ===== */

.container-fluid {
    padding-left: 130px !important;
    padding-right: 25px !important;
    margin-top: 50px;
}

/* Agar sidebar fixed width 250px hai */
.main-content {
    margin-left: 250px;
    transition: all 0.3s ease;
}

/* Card full width */
.card {
    width: 100%;
}

/* Prevent overflow */
body {
    overflow-x: hidden;
}

.avatar-sm {
    width: 35px;
    height: 35px;
    font-size: 14px;
    font-weight: bold;
}

.form-control:focus, .form-select:focus { 
    border-color: #667eea; 
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25); 
}
.btn-primary { 
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
    border: none; 
}
</style>

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h4 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Salary Management</h4>
                </div>
                <div class="card-body">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(isset($newSalaries) && count($newSalaries) > 0)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-bell me-2"></i>
                            <strong>New Salary Generated!</strong> 
                            {{ count($newSalaries) }} employee(s) salary has been automatically generated for last month.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('admin.salary.index') }}" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="month" class="form-label fw-bold">Select Month</label>
                                    <select name="month" id="month" class="form-select">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="year" class="form-label fw-bold">Select Year</label>
                                    <select name="year" id="year" class="form-select">
                                        @for($i = 2020; $i <= 2030; $i++)
                                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-search me-2"></i>View Salary
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <form method="POST" action="{{ route('admin.salary.generate') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="month" value="{{ $month }}">
                                        <input type="hidden" name="year" value="{{ $year }}">
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to generate salary for {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}?')">
                                            <i class="fas fa-calculator me-2"></i>Generate Salary
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-warning" onclick="setDefaultSalaries()">
                                        <i class="fas fa-cog me-2"></i>Set Default Salaries
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <form method="POST" action="{{ route('admin.salary.auto-generate') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-info" onclick="return confirm('This will auto-generate salary for last month. Continue?')">
                                            <i class="fas fa-magic me-2"></i>Auto Generate
                                        </button>
                                    </form>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Salary Table -->
                    @if(count($salaryRecords) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>SN</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Shift</th>
                                        <th>Total Working Days</th>
                                        <th>In-Hand Salary</th>
                                        <th>Net Take Home</th>
                                        <th>Action</th>
                                        <th>Breakdown</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salaryRecords as $index => $record)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        {{ strtoupper(substr($record->employee->first_name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $record->employee->first_name }} {{ $record->employee->last_name }}</div>
                                                        <small class="text-muted">ID: {{ $record->employee->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $record->employee->job_title ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $record->shift == 'Day' ? 'bg-warning' : 'bg-info' }}">
                                                    {{ $record->shift ?? 'Day' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">{{ number_format($record->working_days, 1) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">₹{{ number_format($record->basic_salary, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">₹{{ number_format($record->net_salary, 2) }}</span>
                                                @if($record->deduction > 0)
                                                    <br><small class="text-warning">Not Earned: ₹{{ number_format($record->deduction, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.salary.view', $record->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('admin.salary.slip', $record->id) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> Slip
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="showBreakdown('{{ $record->employee->first_name }} {{ $record->employee->last_name }}', {{ $record->employee->current_ctc ?? 0 }}, {{ $record->employee->in_hand_salary ?? 0 }}, {{ $record->basic_salary }}, {{ $record->net_salary }}, {{ $record->working_days }}, {{ $record->deduction ?? 0 }}, {{ $record->advance ?? 0 }}, {{ $record->incentive ?? 0 }}, {{ $record->employee_pf ?? 0 }}, {{ $record->employee_esi ?? 0 }}, {{ $record->employer_pf ?? 0 }}, {{ $record->employer_esi ?? 0 }})">
                                                    <i class="fas fa-chart-pie"></i> Breakdown
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="5">Total</th>
                                        <th>₹{{ number_format($salaryRecords->sum('basic_salary'), 2) }}</th>
                                        <th>₹{{ number_format($salaryRecords->sum('net_salary'), 2) }}</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No salary records found</h5>
                            <p class="text-muted">Select month and year, then click "Generate Salary" to create salary records.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Breakdown Modal -->
<div class="modal fade" id="breakdownModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title"><i class="fas fa-chart-line me-2"></i>Salary Breakdown</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <h4 id="employeeName" class="text-primary mb-2"></h4>
                    <div class="badge bg-info fs-6 px-3 py-2">Detailed Salary Calculation</div>
                </div>
                
                <!-- Calculation Steps -->
                <div class="card mb-4" style="border-left: 4px solid #667eea;">
                    <div class="card-body">
                        <h6 class="card-title text-primary"><i class="fas fa-calculator me-2"></i>Calculation Method</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <div class="mb-1"><span class="badge bg-light text-dark me-2">1</span>Gross calculated from In-Hand</div>
                                    <div class="mb-1"><span class="badge bg-light text-dark me-2">2</span>Basic = 60% of Gross</div>
                                    <div class="mb-1"><span class="badge bg-light text-dark me-2">3</span>Employee PF = 12% of Basic</div>
                                    <div class="mb-1"><span class="badge bg-light text-dark me-2">4</span>Employee ESI = 0.75% of Gross</div>
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <div class="mb-1"><span class="badge bg-light text-dark me-2">5</span>Employer PF = 13% of Basic</div>
                                    <div class="mb-1"><span class="badge bg-light text-dark me-2">6</span>CTC = Gross + Employer Contributions</div>
                                    <div class="mb-1"><span class="badge bg-light text-dark me-2">7</span>Final = Net + Advance + Incentive</div>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Salary Info -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center" style="background: linear-gradient(135deg, #6f42c1, #5a2d91); color: white; border-radius: 8px;">
                                <i class="fas fa-calculator fa-2x mb-2"></i>
                                <h6 class="card-title">Gross Salary</h6>
                                <h4 id="grossSalary"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center" style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; border-radius: 8px;">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <h6 class="card-title">Total CTC</h6>
                                <h4 id="ctcSalary"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center" style="background: linear-gradient(135deg, #2eacb3, #0056b3); color: white; border-radius: 8px;">
                                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                                <h6 class="card-title">In-Hand Salary</h6>
                                <h4 id="inHandSalary"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center" style="background: linear-gradient(135deg, #ffc107, #e0a800); color: white; border-radius: 8px;">
                                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                <h6 class="card-title">Working Days</h6>
                                <h4 id="workingDays"></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center" style="background: linear-gradient(135deg, #28a745, #1e7e34); color: white; border-radius: 8px;">
                                <i class="fas fa-coins fa-2x mb-2"></i>
                                <h6 class="card-title">Final Take Home</h6>
                                <h3 id="netSalary"></h3>
                                <small>Net + Advance + Incentive</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-success mb-3"><i class="fas fa-plus-circle me-2"></i>Additions</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-gift text-success mb-2"></i>
                                <h6 class="card-title text-success">Advance</h6>
                                <h5 id="advance" class="text-success"></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-trophy text-success mb-2"></i>
                                <h6 class="card-title text-success">Incentive</h6>
                                <h5 id="incentive" class="text-success"></h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Employee Deductions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-danger mb-3"><i class="fas fa-minus-circle me-2"></i>Employee Deductions</h6>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <i class="fas fa-piggy-bank text-danger mb-2"></i>
                                <h6 class="card-title text-danger">Employee PF</h6>
                                <small class="text-muted">12% of Basic (max ₹15k)</small>
                                <h5 id="employeePf" class="text-danger mt-2"></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <i class="fas fa-shield-alt text-danger mb-2"></i>
                                <h6 class="card-title text-danger">Employee ESI</h6>
                                <small class="text-muted">0.75% of Gross (if ≤ ₹21k)</small>
                                <h5 id="employeeEsi" class="text-danger mt-2"></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-times text-warning mb-2"></i>
                                <h6 class="card-title text-warning">Absent Deduction</h6>
                                <small class="text-muted">Based on working days</small>
                                <h5 id="deduction" class="text-warning mt-2"></h5>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Employer Contributions -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-secondary mb-3"><i class="fas fa-building me-2"></i>Employer Contributions</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-secondary">
                            <div class="card-body text-center">
                                <i class="fas fa-university text-secondary mb-2"></i>
                                <h6 class="card-title text-secondary">Employer PF</h6>
                                <small class="text-muted">13% of Basic</small>
                                <h5 id="employerPf" class="text-secondary mt-2"></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-secondary">
                            <div class="card-body text-center">
                                <i class="fas fa-hospital text-secondary mb-2"></i>
                                <h6 class="card-title text-secondary">Employer ESI</h6>
                                <small class="text-muted">3.25% of Gross</small>
                                <h5 id="employerEsi" class="text-secondary mt-2"></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function setDefaultSalaries() {
    alert('Please set CTC and In-Hand salary manually in employee records from Admin Panel!');
}

function showBreakdown(name, ctc, inHandSalary, basicSalary, netSalary, workingDays, deduction, advance, incentive, employeePf, employeeEsi, employerPf, employerEsi) {
    console.log('Breakdown data:', {name, ctc, inHandSalary, basicSalary, netSalary, workingDays, deduction, advance, incentive, employeePf, employeeEsi, employerPf, employerEsi});
    
    // Calculate gross salary from in-hand (reverse calculation)
    var grossSalary = calculateGrossFromInHand(parseFloat(inHandSalary || 0));
    
    document.getElementById('employeeName').textContent = name + ' - Salary Breakdown';
    document.getElementById('grossSalary').textContent = '₹' + grossSalary.toLocaleString();
    document.getElementById('ctcSalary').textContent = '₹' + parseFloat(ctc || 0).toLocaleString();
    document.getElementById('inHandSalary').textContent = '₹' + parseFloat(inHandSalary || 0).toLocaleString();
    document.getElementById('workingDays').textContent = parseFloat(workingDays || 0);
    document.getElementById('advance').textContent = '₹' + parseFloat(advance || 0).toLocaleString();
    document.getElementById('incentive').textContent = '₹' + parseFloat(incentive || 0).toLocaleString();
    document.getElementById('employeePf').textContent = '₹' + parseFloat(employeePf || 0).toLocaleString();
    document.getElementById('employeeEsi').textContent = '₹' + parseFloat(employeeEsi || 0).toLocaleString();
    document.getElementById('employerPf').textContent = '₹' + parseFloat(employerPf || 0).toLocaleString();
    document.getElementById('employerEsi').textContent = '₹' + parseFloat(employerEsi || 0).toLocaleString();
    document.getElementById('deduction').textContent = '₹' + parseFloat(deduction || 0).toLocaleString();
    
    // Calculate final net salary with advance and incentive
    var finalNetSalary = parseFloat(netSalary || 0) + parseFloat(advance || 0) + parseFloat(incentive || 0);
    document.getElementById('netSalary').textContent = '₹' + finalNetSalary.toLocaleString();
    
    new bootstrap.Modal(document.getElementById('breakdownModal')).show();
}

function calculateGrossFromInHand(inHand) {
    // Iterative approach to find gross that results in desired in-hand
    var gross = inHand;
    
    for (var i = 0; i < 10; i++) {
        var basic = gross * 0.60;
        var pfBasic = (basic >= 15000) ? 15000 : basic;
        var employeePf = pfBasic * 0.12;
        
        var employeeEsic = (gross <= 21000) ? gross * 0.0075 : 0;
        
        var calculatedInHand = gross - employeePf - employeeEsic;
        
        if (Math.abs(calculatedInHand - inHand) < 0.01) {
            break;
        }
        
        gross = gross + (inHand - calculatedInHand);
    }
    
    return Math.round(gross * 100) / 100;
}
</script>