<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apeheducation extends Model
{
    use HasFactory;

    protected $table = 'apeheducation';

    protected $primaryKey = 'apehid';

    protected $fillable = [
        'casecode',
        'schoollevel',
        'schoolname',
        'ingrade',
        'strand',
        'section',
        'gwa',
        'gwaconduct',
        'chinesegwa',
        'chinesegwaconduct',
    ];

    public function applicant()
    {
        return $this->belongsTo(applicants::class, 'casecode', 'casecode');
    }
}
