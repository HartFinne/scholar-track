<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class evalresults extends Model
{
    use HasFactory;

    protected $table = 'evalresults';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'acadyear',
        'evalscore',
        'isPassed',
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
}
