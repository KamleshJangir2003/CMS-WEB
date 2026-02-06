<?php

// app/Models/EmployeeDocument.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'document_name',
        'file_path',
        'file_size',
        'file_extension',
        'status',
        'expiry_date',
        'remarks',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'pending',
            'uploaded' => 'verified',
            'verified' => 'verified',
            'expired' => 'expired',
            'rejected' => 'expired',
        ];

        return $badges[$this->status] ?? 'pending';
    }
}
