<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ScEducation extends Model
{
    use HasFactory;

    protected $table = 'sc_education';

    protected $primaryKey = 'eid';

    protected $fillable = [
        'caseCode',
        'scSchoolLevel',
        'scSchoolName',
        'scYearGrade',
        'scCourseStrandSec',
        'scCollegedept',
        'scAcademicYear'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'caseCode', 'caseCode');
    }

    public function renewal()
    {
        return $this->hasMany(renewal::class, 'caseCode', 'caseCode');
    }
}
