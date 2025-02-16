<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetModel extends Model
{
    use HasFactory;

    protected $table = 'passwordreset';

    protected $fillable = [
        'user_id',
        'otp'
    ];
}
