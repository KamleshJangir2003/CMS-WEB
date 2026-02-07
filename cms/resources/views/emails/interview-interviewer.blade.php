<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Interview Assignment</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #16a34a; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .candidate-info { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Interview Assignment</h1>
        </div>
        
        <div class="content">
            <p>Dear Interviewer,</p>
            
            <p>You have been assigned to conduct an interview.</p>
            
            <div class="candidate-info">
                <h3>👤 Candidate Information:</h3>
                <p><strong>Name:</strong> {{ $interview->candidate_name }}</p>
                <p><strong>Email:</strong> {{ $interview->candidate_email ?? 'Not provided' }}</p>
                <p><strong>Phone:</strong> {{ $lead->number }}</p>
                <p><strong>Role:</strong> {{ $interview->job_role }}</p>
            </div>
            
            <div class="details">
                <h3>Interview Details:</h3>
                <p><strong>📅 Date:</strong> {{ date('d M Y', strtotime($interview->interview_date)) }}</p>
                <p><strong>⏰ Time:</strong> {{ $interview->start_time }} - {{ $interview->end_time }}</p>
                <p><strong>🔄 Round:</strong> {{ $interview->interview_round }}</p>
                <p><strong>💻 Mode:</strong> {{ $interview->interview_mode }}</p>
                @if($interview->meeting_platform)
                    <p><strong>🖥️ Platform:</strong> {{ $interview->meeting_platform }}</p>
                @endif
                @if($interview->meeting_link)
                    <p><strong>🔗 Meeting Link:</strong> <a href="{{ $interview->meeting_link }}" target="_blank">{{ $interview->meeting_link }}</a></p>
                @endif
            </div>
            
            @if($interview->instructions)
            <div class="details">
                <h3>📝 Instructions:</h3>
                <p>{{ $interview->instructions }}</p>
            </div>
            @endif
            
            <p>Please be prepared and join on time. 👍</p>
        </div>
        
        <div class="footer">
            <p>Best regards,<br>Kwikster HR Team</p>
        </div>
    </div>
</body>
</html>