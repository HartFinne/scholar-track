<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScGrade extends Model
{
    use HasFactory;
    protected $table = 'sc_grades';

    public $timestamps = false;

    protected $fillable = [
        'caseCode',
        'scAcademicYear',
        'scSemester',
        'scGWA',
        'scReportCard',
        'scGradeStatus'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
