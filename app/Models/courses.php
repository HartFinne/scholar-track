<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $primaryKey = 'coid';

    protected $fillable = [
        'level',
        'coursename',
    ];
}
