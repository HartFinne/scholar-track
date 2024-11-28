<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class renewal extends Model
{
    use HasFactory;

    protected $table = 'renewal';

    protected $primaryKey = 'rid';

    protected $fillable = [
        'caseCode',
        'datesubmitted',
        'picture',
        'reportcard',
        'utilitybill',
        'sketchaddress',
        'reflectionpaper'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->hasOne(User::class, 'caseCode', 'caseCode');
    }
}
