<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Offer Letter - Kwikster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.7;
            color: #2c3e50;
            background: #f4f6f9;
            padding: 20px;
        }
        .email-container {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2eacb3 0%, #0056b3 100%);
            color: white;
            padding: 50px 40px;
            text-align: center;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #0056b3;
        }
        .company-name {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .company-address {
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.6;
        }
        .content {
            padding: 50px 40px;
        }
        .congratulations {
            text-align: center;
            margin-bottom: 40px;
        }
        .congratulations h2 {
            font-size: 32px;
            color: #2eacb3;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .congratulations .emoji {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }
        .intro-text {
            font-size: 18px;
            margin-bottom: 35px;
            color: #34495e;
            text-align: center;
            line-height: 1.8;
        }
        .offer-details {
            background: linear-gradient(135deg, #e7f3ff 0%, #cce7ff 100%);
            padding: 35px;
            border-radius: 12px;
            margin: 30px 0;
            border: 1px solid #2eacb3;
        }
        .offer-details h3 {
            color: #2eacb3;
            font-size: 22px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0,123,255,0.1);
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }
        .detail-value {
            font-weight: 700;
            color: #2eacb3;
            font-size: 16px;
        }
        .instructions {
            background: #fff8e1;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            border-left: 5px solid #ffc107;
        }
        .instructions p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        .contact-info {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
        }
        .contact-info p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .contact-info a {
            color: #2eacb3;
            text-decoration: none;
            font-weight: 600;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
        .closing {
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            color: #27ae60;
            margin: 35px 0;
            padding: 25px;
            background: #e8f5e8;
            border-radius: 12px;
        }
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px;
            text-align: center;
        }
        .footer-content {
            margin-bottom: 20px;
        }
        .footer-content p {
            margin-bottom: 8px;
            font-size: 16px;
        }
        .footer-note {
            font-size: 14px;
            opacity: 0.8;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 20px;
            margin-top: 20px;
        }
        @media (max-width: 600px) {
            .email-container { margin: 10px; border-radius: 12px; }
            .header { padding: 30px 20px; }
            .content { padding: 30px 25px; }
            .offer-details, .instructions, .contact-info { padding: 20px; }
            .detail-row { flex-direction: column; align-items: flex-start; gap: 5px; }
            .congratulations h2 { font-size: 24px; }
            .company-name { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="company-name">Kwikster Innovative Optimisations Pvt Ltd</div>
            <div class="company-address">
                21/281, Kaveri path, Madhyam Marg Road<br>
                Mansarovar, Jaipur, Rajasthan, 302020
            </div>
        </div>

        <div class="content">
            <div class="congratulations">
                <span class="emoji">🎉</span>
                <h2>Congratulations {{ $employee->first_name }}!</h2>
            </div>
            
            <div class="intro-text">
                We are delighted to inform you that your offer letter has been generated and is attached to this email. Welcome to the Kwikster family!
            </div>
            
            <div class="offer-details">
                <h3>💼 Position Details</h3>
                <div class="detail-row">
                    <span class="detail-label">🎯 Position:</span>
                    <span class="detail-value">{{ $employee->department ?? 'Lead Generation Executive' }}</span>
                </div>
                @if($employee->current_ctc)
                <div class="detail-row">
                    <span class="detail-label">💰 Annual CTC:</span>
                    <span class="detail-value">₹{{ number_format($employee->current_ctc, 0) }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">📅 Joining Date:</span>
                    <span class="detail-value">{{ $employee->joining_date ? $employee->joining_date->format('jS F, Y') : 'To be confirmed' }}</span>
                </div>
            </div>

            <div class="instructions">
                <p><strong>📝 Important:</strong> Please review the attached offer letter carefully and respond within <strong>7 days</strong> of receiving this email.</p>
                <p>Your prompt response will help us proceed with the onboarding process smoothly.</p>
            </div>

            <div class="contact-info">
                <p><strong>📧 Questions or Concerns?</strong></p>
                <p>Feel free to contact our HR team at <a href="mailto:hr@thekwikster.com">hr@thekwikster.com</a></p>
                <p>We're here to help and answer any questions you may have.</p>
            </div>

            <div class="closing">
                🎆 We look forward to welcoming you to our team and starting this exciting journey together!
            </div>
        </div>

        <div class="footer">
            <div class="footer-content">
                <p><strong>Best regards,</strong></p>
                <p><strong>HR Team</strong></p>
                <p><strong>Kwikster Innovative Optimisations Pvt Ltd</strong></p>
            </div>
            
            <div class="footer-note">
                <p>This is an automated email. Please do not reply to this email address.</p>
                <p>For any queries, please contact us at hr@thekwikster.com</p>
            </div>
        </div>
    </div>
</body>
</html>