<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointments extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'reason',
        'date',
        'time',
        'status',
        'updatedby'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
    public function education()
    {
        return $this->hasOne(ScEducation::class, 'caseCode', 'caseCode');
    }
    public function basicInfo()
    {
        return $this->hasOne(ScBasicInfo::class, 'caseCode', 'caseCode');
    }
}
