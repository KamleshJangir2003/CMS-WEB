<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestedCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name', 
        'email',
        'role',
        'platform',
        'resume',
        'status',
        'notes',
        'interested_at'
    ];

    protected $casts = [
        'interested_at' => 'datetime',
    ];
}