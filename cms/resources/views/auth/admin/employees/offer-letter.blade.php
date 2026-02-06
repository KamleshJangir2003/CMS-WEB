<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offer Letter - {{ $employee->full_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .company-address {
            font-size: 12px;
            color: #666;
        }
        .letter-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 30px 0;
            text-decoration: underline;
        }
        .date {
            text-align: right;
            margin-bottom: 20px;
        }
        .employee-details {
            margin-bottom: 20px;
        }
        .content {
            text-align: justify;
            margin-bottom: 20px;
        }
        .terms {
            margin: 20px 0;
        }
        .terms-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .terms-list {
            margin-left: 20px;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">KWIKSTER TECHNOLOGIES</div>
        <div class="company-address">
            123 Business Street, Tech City, State - 123456<br>
            Phone: +91 9876543210 | Email: hr@kwikster.com
        </div>
    </div>

    <div class="date">
        Date: {{ date('d F, Y') }}
    </div>

    <div class="employee-details">
        <strong>{{ $employee->full_name }}</strong><br>
        {{ $employee->address ?? 'Address on file' }}<br>
        {{ $employee->city ?? '' }} {{ $employee->state ?? '' }} - {{ $employee->pincode ?? '' }}<br>
        Phone: {{ $employee->phone }}<br>
        Email: {{ $employee->email }}
    </div>

    <div class="letter-title">OFFER LETTER</div>

    <div class="content">
        <p>Dear {{ $employee->first_name }},</p>

        <p>We are pleased to offer you the position of <strong>{{ ucfirst($employee->department) }}</strong> at Kwikster Technologies. We believe that your skills and experience will be a valuable addition to our team.</p>

        <p>This offer is contingent upon the successful completion of all pre-employment requirements, including but not limited to background verification, document verification, and any other requirements as deemed necessary by the company.</p>
    </div>

    <div class="terms">
        <div class="terms-title">Terms and Conditions:</div>
        <div class="terms-list">
            <p><strong>1. Position:</strong> {{ ucfirst($employee->department) }}</p>
            <p><strong>2. Department:</strong> {{ ucfirst($employee->department) }}</p>
            <p><strong>3. Reporting:</strong> As per company hierarchy</p>
            <p><strong>4. Probation Period:</strong> 6 months from the date of joining</p>
            <p><strong>5. Working Hours:</strong> 9:00 AM to 6:00 PM, Monday to Friday</p>
            <p><strong>6. Benefits:</strong> As per company policy</p>
            @if($bankDetail)
            <p><strong>7. Bank Details on File:</strong><br>
               Bank: {{ $bankDetail->bank_name }}<br>
               Account: {{ $bankDetail->account_number }}<br>
               IFSC: {{ $bankDetail->ifsc_code }}
            </p>
            @endif
        </div>
    </div>

    <div class="content">
        <p>Please confirm your acceptance of this offer by signing and returning a copy of this letter within 7 days of receipt. We look forward to welcoming you to our team.</p>

        <p>If you have any questions regarding this offer, please feel free to contact our HR department.</p>

        <p>Welcome to Kwikster Technologies!</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                HR Manager<br>
                Kwikster Technologies
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Employee Signature<br>
                {{ $employee->full_name }}
            </div>
        </div>
    </div>

    <div class="footer">
        <p>This is a computer-generated document and does not require a physical signature.</p>
        <p>Generated on {{ date('d F, Y \a\t H:i:s') }}</p>
    </div>
</body>
</html>