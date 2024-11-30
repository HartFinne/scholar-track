<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RnwCaseDetails extends Model
{
    use HasFactory;

    protected $table = 'rnwcasedetails';

    protected $primaryKey = 'rcdid';

    protected $fillable = [
        'rid',
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

    public function renewal()
    {
        return $this->belongsTo(renewal::class, 'rid', 'rid');
    }
}
