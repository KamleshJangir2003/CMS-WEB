<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Kwikster</title>
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
        .welcome-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .company-subtitle {
            font-size: 18px;
            opacity: 0.95;
            font-weight: 500;
        }
        .content {
            padding: 50px 40px;
        }
        .greeting {
            font-size: 20px;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        .congratulations {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
            border-radius: 12px;
            border: 1px solid #27ae60;
        }
        .congratulations h3 {
            font-size: 24px;
            color: #27ae60;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .congratulations .emoji {
            font-size: 36px;
            margin-bottom: 15px;
            display: block;
        }
        .intro-text {
            font-size: 16px;
            margin-bottom: 35px;
            color: #34495e;
            line-height: 1.8;
        }
        .position-highlight {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            padding: 20px;
            border-radius: 8px;
            display: inline-block;
            font-weight: 600;
            color: #f57c00;
            border: 1px solid #ff9800;
        }
        .office-address {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
            padding: 35px;
            border-radius: 12px;
            margin: 30px 0;
            border-left: 5px solid #2eacb3;
        }
        .office-address h4 {
            color: #2eacb3;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .address-text {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .joining-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 15px 0;
            border-top: 1px solid rgba(0,123,255,0.2);
        }
        .joining-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }
        .joining-date {
            font-weight: 700;
            color: #2eacb3;
            font-size: 18px;
        }
        .documents-section {
            background: #fff8e1;
            padding: 35px;
            border-radius: 12px;
            margin: 30px 0;
            border-left: 5px solid #ffc107;
        }
        .documents-section h4 {
            color: #f57c00;
            font-size: 20px;
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .documents-list {
            list-style: none;
            padding: 0;
        }
        .documents-list li {
            padding: 8px 0;
            border-bottom: 1px solid rgba(245,124,0,0.1);
            font-size: 15px;
            color: #2c3e50;
            position: relative;
            padding-left: 25px;
        }
        .documents-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #27ae60;
            font-weight: bold;
            font-size: 16px;
        }
        .documents-list li:last-child {
            border-bottom: none;
        }
        .support-section {
            background: #e3f2fd;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
        }
        .support-section p {
            margin-bottom: 10px;
            font-size: 16px;
            color: #1976d2;
        }
        .closing-message {
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
        .company-name {
            font-weight: 700;
            font-size: 18px;
        }
        @media (max-width: 600px) {
            .email-container { margin: 10px; border-radius: 12px; }
            .header { padding: 30px 20px; }
            .content { padding: 30px 25px; }
            .office-address, .documents-section, .support-section { padding: 25px 20px; }
            .joining-info { flex-direction: column; align-items: flex-start; gap: 10px; }
            .welcome-title { font-size: 24px; }
            .congratulations h3 { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="welcome-title">🎆 Welcome to Kwikster</div>
            <div class="company-subtitle">Innovative Optimisations Pvt. Ltd.</div>
        </div>
        
        <div class="content">
            <div class="greeting">
                Dear <strong>{{ $candidateName }}</strong>,
            </div>
            
            <div class="congratulations">
                <span class="emoji">🎉</span>
                <h3>Congratulations and Welcome!</h3>
            </div>
            
            <div class="intro-text">
                We are pleased to inform you that you have successfully cleared all stages of our selection process and have been selected to join <strong>Kwikster Innovative Optimisations Pvt. Ltd.</strong> as an <span class="position-highlight"> Insurance Consultant</span>. We are excited to welcome you to our organization and look forward to your valuable contributions to our team.
            </div>
            
            <p style="font-size: 16px; margin-bottom: 25px; color: #34495e;">You are requested to report to our office at the following address:</p>
            
            <div class="office-address">
                <h4>🏢 Office Location</h4>
                <div class="address-text">
                    21/284, Kaveri Path, Sector 21, Mansarovar<br>
                    Jaipur, Rajasthan – 302020
                </div>
                <div class="joining-info">
                    <span class="joining-label">📅 Joining Date:</span>
                    <span class="joining-date">{{ $joiningDate }}</span>
                </div>
            </div>
            
            <p style="font-size: 16px; margin-bottom: 25px; color: #34495e;">To ensure a smooth onboarding process, please bring the following documents for verification and HR formalities:</p>
            
            <div class="documents-section">
                <h4>📝 Mandatory Documents</h4>
                <ul class="documents-list">
                    <li>Updated resume</li>
                    <li>Aadhaar Card (original + 1 photocopy)</li>
                    <li>PAN Card (original + 1 photocopy)</li>
                    <li>Two passport-size photographs</li>
                    <li>Educational certificates (10th, 12th, and Graduation – copies)</li>
                    <li>Bank passbook or cancelled cheque</li>
                    <li>Address proof (if different from Aadhaar)</li>
                    <li>Previous company offer letter/experience letter (if applicable)</li>
                    <li>Last three months' salary slips (for experienced candidates)</li>
                </ul>
            </div>
            
            <div class="support-section">
                <p><strong>📞 Need Assistance?</strong></p>
                <p>Should you have any questions or require assistance, please feel free to contact the HR department. We are happy to support you at every step.</p>
            </div>
            
            <div class="closing-message">
                🌟 We wish you a rewarding and successful career with us and look forward to a long and mutually beneficial association.
            </div>
        </div>
        
        <div class="footer">
            <div class="footer-content">
                <p><strong>Best regards,</strong></p>
                <p><strong>HR Team</strong></p>
                <p class="company-name">Kwikster Innovative Optimisations Pvt. Ltd.</p>
            </div>
        </div>
    </div>
</body>
</html>