<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reports extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reportname',
        'datagenerated',
        'generatedby',
        'filepath'
    ];
}
