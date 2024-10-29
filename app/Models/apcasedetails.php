<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apcasedetails extends Model
{
    use HasFactory;

    protected $table = 'apcasedetails';

    protected $primaryKey = 'apcdid';

    protected $fillable = [
        'casecode',
        'natureofneeds',
        'problemstatement',
        'receivedby',
        'datereceived',
        'district',
        'volunteer',
        'referredby',
        'referphonenum',
        'relationship',
        'datereported'
    ];

    public function applicant()
    {
        return $this->belongsTo(applicants::class, 'casecode', 'casecode');
    }
}
