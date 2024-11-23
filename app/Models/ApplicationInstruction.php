<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationInstruction extends Model
{
    use HasFactory;

    protected $table = 'application_instructions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'status',
        'applicants',
        'qualifications',
        'requireddocuments',
        'applicationprocess',
    ];
}
