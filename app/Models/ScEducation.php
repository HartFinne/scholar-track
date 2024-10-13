<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ScEducation extends Model
{
    use HasFactory;

    protected $table = 'sc_education';

    protected $fillable = [
        'caseCode',
        'scSchoolLevel',
        'scSchoolName',
        'scYearGrade',
        'scCourseStrandSec',
        'scAcademicYear'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
