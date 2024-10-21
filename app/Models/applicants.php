<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class applicants extends Model
{
    use HasFactory;

    protected $table = 'applicants';

    protected $primaryKey = 'apid';

    protected $fillable = [
        'casecode',
        'name',
        'chinesename',
        'age',
        'sex',
        'birthdate',
        'homeaddress',
        'barangay',
        'city',
        'email',
        'phonenum',
        'occupation',
        'income',
        'fblink',
        'isIndigenous',
        'indigenousgroup',
        'applicationstatus',
        'prioritylevel',
    ];

    public function familyinfo()
    {
        return $this->hasMany(apfamilyinfo::class, 'casecode', 'casecode');
    }

    public function educcollege()
    {
        return $this->hasOne(apceducation::class, 'casecode', 'casecode');
    }

    public function educelemhs()
    {
        return $this->hasOne(apeheducation::class, 'casecode', 'casecode');
    }

    public function otherinfo()
    {
        return $this->hasOne(apotherinfo::class, 'casecode', 'casecode');
    }

    public function requirements()
    {
        return $this->hasOne(aprequirements::class, 'casecode', 'casecode');
    }

    public function casedetails()
    {
        return $this->hasOne(apcasedetails::class, 'casecode', 'casecode');
    }
}
