<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class datasets extends Model
{
    use HasFactory;

    protected $table = 'datasets';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'acadcycle',
        'startcontract',
        'endcontract',
        'gwasem1',
        'gwasem2',
        'gwasem3',
        'cshours',
        // 'ltecount',
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
}
