<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'name',
        'role',
        'callback_date',
        'notes'
    ];

    protected $casts = [
        'callback_date' => 'datetime'
    ];
}