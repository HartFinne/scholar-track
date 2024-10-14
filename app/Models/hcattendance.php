<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hcattendance extends Model
{
    use HasFactory;

    protected $table = 'hcattendance';

    protected $primaryKey = 'hcaid';

    protected $fillable = [
        'hcid',
        'caseCode',
        'timein',
        'timeout',
        'tardinessduration',
        'hcastatus'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
