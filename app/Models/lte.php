<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lte extends Model
{
    use HasFactory;

    protected $table = 'lte';

    protected $primaryKey = 'lid';

    protected $fillable = [
        'caseCode',
        'violation',
        'conditionid',
        'eventtype',
        'dateissued',
        'deadline',
        'datesubmitted',
        'reason',
        'explanation',
        'proof',
        'ltestatus',
        'workername'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function csattendance()
    {
        return $this->belongsTo(csattendance::class, 'conditionid', 'csaid');
    }

    public function csregistration()
    {
        return $this->belongsTo(csregistration::class, 'conditionid', 'csrid');
    }

    public function hcattendance()
    {
        return $this->belongsTo(hcattendance::class, 'conditionid', 'hcaid');
    }

    public function penalty()
    {
        return $this->belongsTo(penalty::class, 'condition', 'lid');
    }

    public function education()
    {
        return $this->belongsTo(ScEducation::class, 'caseCode', 'caseCode');
    }
}
