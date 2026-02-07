<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'candidate_name',
        'candidate_email',
        'job_role',
        'interview_round',
        'interview_date',
        'start_time',
        'end_time',
        'interviewer',
        'interview_mode',
        'meeting_platform',
        'meeting_link',
        'instructions',
        'email_candidate',
        'email_interviewer',
        'whatsapp_notification',
        'status',
        'result',
        'rejection_reason',
        'current_ctc',
        'expected_ctc',
        'offered_ctc',
        'offer_status'
    ];

    protected $casts = [
        'interview_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'email_candidate' => 'boolean',
        'email_interviewer' => 'boolean',
        'whatsapp_notification' => 'boolean',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}