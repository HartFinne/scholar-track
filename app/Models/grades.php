<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grades extends Model
{
    use HasFactory;

    protected $table = 'grades';

    protected $primaryKey = 'gid';

    protected $fillable = [
        'caseCode',
        'AcademicYear',
        'SemesterQuarter',
        'GWA',
        'ReportCard',
        'GradeStatus',
    ];
}
