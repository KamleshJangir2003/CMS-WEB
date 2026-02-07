<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Interview Scheduled</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4f46e5; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .details { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .meeting-link { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .footer { text-align: center; padding: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Interview Scheduled</h1>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $lead->name }}</strong>,</p>
            
            <p>Your interview has been scheduled for the <strong>{{ $interview->job_role }}</strong> position.</p>
            
            <div class="details">
                <h3>Interview Details:</h3>
                <p><strong>📅 Date:</strong> {{ date('d M Y', strtotime($interview->interview_date)) }}</p>
                <p><strong>⏰ Time:</strong> {{ $interview->start_time }} - {{ $interview->end_time }}</p>
                <p><strong>👤 Interviewer:</strong> {{ $interview->interviewer }}</p>
                <p><strong>🔄 Round:</strong> {{ $interview->interview_round }}</p>
                <p><strong>💻 Mode:</strong> {{ $interview->interview_mode }}</p>
                @if($interview->meeting_platform)
                    <p><strong>🖥️ Platform:</strong> {{ $interview->meeting_platform }}</p>
                @endif
            </div>
            
            @if($interview->meeting_link)
            <div class="meeting-link">
                <h3>🔗 Meeting Link:</h3>
                <p><a href="{{ $interview->meeting_link }}" target="_blank">{{ $interview->meeting_link }}</a></p>
                <p><small>Please join 10 minutes before the scheduled time.</small></p>
            </div>
            @endif
            
            @if($interview->instructions)
            <div class="details">
                <h3>📝 Instructions:</h3>
                <p>{{ $interview->instructions }}</p>
            </div>
            @endif
            
            <p>Good luck with your interview! 🍀</p>
        </div>
        
        <div class="footer">
            <p>Best regards,<br>Kwikster Team</p>
        </div>
    </div>
</body>
</html>