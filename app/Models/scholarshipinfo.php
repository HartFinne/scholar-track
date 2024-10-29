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
}
