<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class applicationforms extends Model
{
    use HasFactory;

    protected $table = 'applicationforms';

    protected $primaryKey = 'afid';

    protected $fillable = [
        'status',
    ];
}
