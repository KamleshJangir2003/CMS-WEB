<!-- resources/views/print/employee-documents.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Documents Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .section { margin-bottom: 25px; }
        .section-title { 
            background: #f3f4f6; 
            padding: 10px; 
            font-weight: bold;
            border-left: 4px solid #3b82f6;
            margin-bottom: 15px;
        }
        .document-row { 
            display: flex; 
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .status { padding: 3px 10px; border-radius: 12px; font-size: 12px; }
        .verified { background: #d1fae5; color: #065f46; }
        .pending { background: #fef3c7; color: #92400e; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        .table th { background: #f3f4f6; }
        .signature-area { 
            margin-top: 50px; 
            display: flex; 
            justify-content: space-between; 
        }
        .signature { 
            width: 45%; 
            border-top: 1px solid #000;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Employee Documents Report</h1>
        <p>Generated on: {{ date('F d, Y') }}</p>
    </div>

    <div class="section">
        <h3>Employee Information</h3>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Employee ID:</strong> {{ $user->employee_id ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Report Date:</strong> {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Document Status Summary</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Document Type</th>
                    <th>Status</th>
                    <th>Upload Date</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td>{{ $doc->document_name }}</td>
                    <td>
                        <span class="status {{ $doc->status_badge }}">{{ ucfirst($doc->status) }}</span>
                    </td>
                    <td>{{ $doc->created_at->format('d-m-Y') }}</td>
                    <td>{{ $doc->remarks ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($bankDetail)
    <div class="section">
        <div class="section-title">Bank Account Details</div>
        <div class="document-row">
            <span>Bank Name:</span>
            <span>{{ $bankDetail->bank_name }}</span>
        </div>
        <div class="document-row">
            <span>Account Number:</span>
            <span>{{ $bankDetail->account_number }}</span>
        </div>
        <div class="document-row">
            <span>IFSC Code:</span>
            <span>{{ $bankDetail->ifsc_code }}</span>
        </div>
        <div class="document-row">
            <span>Account Type:</span>
            <span>{{ ucfirst($bankDetail->account_type) }}</span>
        </div>
    </div>
    @endif

    <div class="signature-area">
        <div class="signature">
            <p><strong>Employee Signature</strong></p>
            <p>Date: ________________</p>
        </div>
        <div class="signature">
            <p><strong>HR Department</strong></p>
            <p>Date: ________________</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>