<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class staccount extends Authenticatable
{
    use Notifiable;

    // Your table and other configurations
    protected $table = 'staccounts';

    protected $fillable = [
        'name',
        'email',
        'mobileno',
        'area',
        'role',
        'status',
        'password'
    ];

    protected $hidden = [
        'password',
    ];

    use HasFactory;
}
