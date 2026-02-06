<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'email',
        'phone', 
        'company',
        'role',
        'status',
        'condition_status'
    ];

    protected $attributes = [
        'status' => 'new',
        'condition_status' => 'Not Interested',
        'role' => 'Unknown'
    ];
}