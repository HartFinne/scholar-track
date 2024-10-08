<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class staccount extends Model
{
    protected $fillable = [
        'name',
        'email',
        'mobileno',
        'area',
        'role',
        'status',
        'password'
    ];
    use HasFactory;
}
