<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class csattendance extends Model
{
    use HasFactory;

    protected $table = 'csattendance';

    protected $primaryKey = 'csaid';

    protected $fillable = [
        'csid',
        'caseCode',
        'timein',
        'timeout',
        'tardinessduration',
        'hoursspent',
        'csastatus',
        'attendanceproof'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function basicInfo()
    {
        return $this->hasOne(ScBasicInfo::class, 'caseCode', 'caseCode');
    }

    public function lte()
    {
        return $this->hasOne(lte::class, 'conditionid', 'csaid');
    }

    public function communityservice()
    {
        return $this->belongsTo(communityservice::class, 'csid', 'csid');
    }
}
