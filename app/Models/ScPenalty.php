<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScPenalty extends Model
{
    use HasFactory;

    protected $table = 'sc_penalties';

    public $timestamps = false;


    protected $fillable = [
        'caseCode',
        'pendCondition',
        'penalty',
        'scYearLevel',
        'dateOfPenalty',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
