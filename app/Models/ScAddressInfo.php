<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScAddressInfo extends Model
{
    use HasFactory;

    protected $table = 'sc_addressinfo';

    protected $primaryKey = 'aid';

    protected $fillable = [
        'caseCode',
        'scResidential',
        'scRegion',
        'scBarangay',
        'scCity'
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

    public function scholarshipinfo()
    {
        return $this->hasOne(scholarshipinfo::class, 'caseCode', 'caseCode');
    }
}
