<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class summaryjhs extends Model
{
    use HasFactory;

    protected $table = 'summaryjhs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'acadcycle',
        'startcontract',
        'endcontract',
        'quarter1',
        'quarter2',
        'quarter3',
        'quarter4',
        'hcabsentcount',
        'penaltycount',
        'remark'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function basicInfo()
    {
        return $this->belongsTo(ScBasicInfo::class, 'caseCode', 'caseCode');
    }

    public function education()
    {
        return $this->belongsTo(ScEducation::class, 'caseCode', 'caseCode');
    }

    public function scholarshipinfo()
    {
        return $this->belongsTo(scholarshipinfo::class, 'caseCode', 'caseCode');
    }
}