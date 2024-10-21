<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apceducation extends Model
{
    use HasFactory;

    protected $table = 'apceducation';

    protected $primaryKey = 'apcid';

    protected $fillable = [
        'casecode',
        'univname',
        'collegedept',
        'inyear',
        'course',
        'gwa',
    ];

    public function applicant()
    {
        return $this->belongsTo(applicants::class, 'casecode', 'casecode');
    }
}
