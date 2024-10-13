<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScBasicInfo extends Model
{
    use HasFactory;

    protected $table = 'sc_basicinfo';

    protected $fillable = [
        'caseCode',
        'scFirstname',
        'scLastname',
        'scMiddlename',
        'scChinesename',
        'scDateOfBirth',
        'scSex',
        'scGuardianName',
        'scRelationToGuardian',
        'scGuardianEmailAddress',
        'scGuardianPhoneNumber',
        'scIsIndigenous',
        'scIndigenousgroup'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
