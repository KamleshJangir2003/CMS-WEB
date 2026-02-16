<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip - {{ $salaryRecord->employee->first_name }} {{ $salaryRecord->employee->last_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .salary-slip {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2eacb3;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2eacb3;
            margin-bottom: 5px;
        }
        .slip-title {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        .period {
            font-size: 14px;
            color: #6c757d;
        }
        .employee-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            flex: 1;
        }
        .info-section h4 {
            color: #2eacb3;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .salary-table th,
        .salary-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .salary-table th {
            background-color: #2eacb3;
            color: white;
            font-weight: bold;
        }
        .salary-table .amount {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 16px;
        }
        .net-salary {
            background-color: #28a745;
            color: white;
        }
        .attendance-section {
            margin-bottom: 30px;
        }
        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        .attendance-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .attendance-count {
            font-size: 20px;
            font-weight: bold;
            color: #2eacb3;
        }
        .attendance-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #6c757d;
            font-size: 12px;
        }
        @media print {
            body { background-color: white; }
            .salary-slip { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="salary-slip">
        <!-- Header -->
        <div class="header">
            <div class="company-name">Your Company Name</div>
            <div class="slip-title">SALARY SLIP</div>
            <div class="period">
                For the month of {{ date('F Y', mktime(0, 0, 0, $salaryRecord->month, 1, $salaryRecord->year)) }}
            </div>
        </div>

        <!-- Employee Information -->
        <div class="employee-info">
            <div class="info-section">
                <h4>Employee Details</h4>
                <table class="info-table">
                    <tr>
                        <td>Employee ID:</td>
                        <td>{{ $salaryRecord->employee->id }}</td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td>{{ $salaryRecord->employee->first_name }} {{ $salaryRecord->employee->last_name }}</td>
                    </tr>
                    <tr>
                        <td>Department:</td>
                        <td>{{ $salaryRecord->employee->department ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Designation:</td>
                        <td>{{ $salaryRecord->employee->job_title ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="info-section">
                <h4>Pay Period</h4>
                <table class="info-table">
                    <tr>
                        <td>Month:</td>
                        <td>{{ date('F', mktime(0, 0, 0, $salaryRecord->month, 1)) }}</td>
                    </tr>
                    <tr>
                        <td>Year:</td>
                        <td>{{ $salaryRecord->year }}</td>
                    </tr>
                    <tr>
                        <td>Total Days:</td>
                        <td>{{ \Carbon\Carbon::create($salaryRecord->year, $salaryRecord->month)->daysInMonth }}</td>
                    </tr>
                    <tr>
                        <td>Working Days:</td>
                        <td>{{ number_format($salaryRecord->working_days, 1) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Attendance Breakdown -->
        <div class="attendance-section">
            <h4 style="color: #2eacb3; margin-bottom: 15px;">Attendance Summary</h4>
            <div class="attendance-grid">
                @foreach($attendanceBreakdown as $status => $count)
                    <div class="attendance-item">
                        <div class="attendance-count">{{ $count }}</div>
                        <div class="attendance-label">{{ $status }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Salary Breakdown -->
        <table class="salary-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary (CTC)</td>
                    <td class="amount">{{ number_format($salaryRecord->basic_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>Per Day Salary</td>
                    <td class="amount">{{ number_format($salaryRecord->basic_salary / \Carbon\Carbon::create($salaryRecord->year, $salaryRecord->month)->daysInMonth, 2) }}</td>
                </tr>
                @if($salaryRecord->deduction > 0)
                <tr style="color: #dc3545;">
                    <td>Total Deductions</td>
                    <td class="amount">- {{ number_format($salaryRecord->deduction, 2) }}</td>
                </tr>
                @endif
                <tr class="net-salary">
                    <td><strong>Net Take Home Salary</strong></td>
                    <td class="amount"><strong>{{ number_format($salaryRecord->net_salary, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated salary slip and does not require a signature.</p>
            <p>Generated on: {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <script>
        // Auto print when opened
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>