<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScBasicInfo extends Model
{
    use HasFactory;

    protected $table = 'sc_basicinfo';

    protected $primaryKey = 'bid';

    protected $fillable = [
        'caseCode',
        'scFirstname',
        'scLastname',
        'scMiddlename',
        'scChinesename',
        'scDateOfBirth',
        'scAge',
        'scSex',
        'scGuardianName',
        'scRelationToGuardian',
        'scGuardianEmailAddress',
        'scGuardianPhoneNumber',
        'scOccupation',
        'scIncome',
        'scFblink',
        'scIsIndigenous',
        'scIndigenousgroup'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function hcattendance()
    {
        return $this->belongsTo(hcattendance::class, 'caseCode', 'caseCode');
    }

    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'caseCode', 'caseCode');
    }

    public function csregistration()
    {
        return $this->hasMany(csregistration::class, 'caseCode', 'caseCode');
    }

    public function csattendance()
    {
        return $this->hasMany(csattendance::class, 'caseCode', 'caseCode');
    }

    public function penalty()
    {
        return $this->hasMany(penalty::class, 'caseCode', 'caseCode');
    }

    public function renewal()
    {
        return $this->hasMany(renewal::class, 'caseCode', 'caseCode');
    }
}
