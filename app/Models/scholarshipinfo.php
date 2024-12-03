<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scholarshipinfo extends Model
{
    use HasFactory;

    protected $table = 'scholarshipinfo';

    protected $primaryKey = 'sid';

    protected $fillable = [
        'caseCode',
        'scholartype',
        'area',
        'startdate',
        'enddate',
        'scholarshipstatus'
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

    public function clothsize()
    {
        return $this->hasOne(ScClothingSize::class, 'caseCode', 'caseCode');
    }

    public function basicinfo()
    {
        return $this->hasOne(ScBasicInfo::class, 'caseCode', 'caseCode');
    }

    public function addressinfo()
    {
        return $this->hasOne(ScAddressInfo::class, 'caseCode', 'caseCode');
    }

    public function renewal()
    {
        return $this->hasOne(renewal::class, 'caseCode', 'caseCode');
    }

    public function csattendance()
    {
        return $this->hasMany(csattendance::class, 'caseCode', 'caseCode');
    }
}
