<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apfamilyinfo extends Model
{
    use HasFactory;

    protected $table = 'apfamilyinfo';

    protected $primaryKey = 'apfid';

    protected $fillable = [
        'casecode',
        'name',
        'age',
        'sex',
        'birthdate',
        'relationship',
        'religion',
        'educattainment',
        'occupation',
        'company',
        'income',
    ];

    public function applicant()
    {
        return $this->belongsTo(applicants::class, 'casecode', 'casecode');
    }
}
