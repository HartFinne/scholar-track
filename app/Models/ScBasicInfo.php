<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScBasicInfo extends Model
{
    use HasFactory;

    protected $table = 'sc_basicinfo';

    public $timestamps = false; // You have created_at and updated_at columns

    protected $fillable = [
        'caseCode',
        'scFirstname',
        'scLastname',
        'scMiddlename',
        'scDateOfBirth',
        'scSex',
        'scGuardianName',
        'scRelationToGuardian',
        'scGuardianEmailAddress',
        'scGuardianPhoneNumber',
        'scIsIndigenous',
        'scScholarshipStatus'
    ];


    // Define inverse relationship to account

}
