<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScEducation extends Model
{
    use HasFactory;

    protected $table = 'sc_education';

    public $timestamps = false;

    protected $fillable = [
        'caseCode',
        'scSchoolLevel',
        'scSchoolName',
        'scYearLevel',
        'scCourseStrand',
        'scSemester'
    ];
}
