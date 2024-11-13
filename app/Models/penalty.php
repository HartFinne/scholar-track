<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penalty extends Model
{
    use HasFactory;

    protected $table = 'penalty';

    protected $primaryKey = 'pid';

    protected $fillable = [
        'caseCode',
        'condition',
        'conditionid',
        'remark',
        'dateofpenalty'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function basicInfo()
    {
        return $this->hasOne(ScBasicInfo::class, 'caseCode', 'caseCode');
    }

    public function lte()
    {
        return $this->hasOne(lte::class, 'condition', 'lid');
    }
}
