<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ScEducation extends Model
{
    use HasFactory;

    protected $table = 'sc_education';

    public $timestamps = false;

    protected $primaryKey = 'scEducationID';

    protected $fillable = [
        'caseCode',
        'scSchoolLevel',
        'scSchoolName',
        'scYearLevel',
        'scCourseStrand',
        'scAcademicYear'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function grades()
    {
        return $this->hasMany(ScGrade::class, 'educationID', 'scEducationID');
    }
}
