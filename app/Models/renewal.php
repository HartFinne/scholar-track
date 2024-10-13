<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class renewal extends Model
{
    use HasFactory;

    protected $table = 'renewal';

    protected $fillable = [
        'caseCode',
        'picture',
        'reportcard',
        'utilitybill',
        'sketchaddress',
        'reflectionpaper'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
