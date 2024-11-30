<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RnwFamilyInfo extends Model
{
    use HasFactory;

    protected $table = 'rnwfamilyinfo';

    protected $primaryKey = 'rfiid';

    protected $fillable = [
        'caseCode',
        'name',
        'age',
        'sex',
        'birthdate',
        'religion',
        'relationship',
        'educattainment',
        'occupation',
        'income',
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
