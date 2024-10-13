<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lte extends Model
{
    use HasFactory;

    protected $table = 'lte';

    protected $fillable = [
        'caseCode',
        'condition',
        'dateissued',
        'deadline',
        'datesubmitted',
        'reason',
        'explanation',
        'proof',
        'ltestatus'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
