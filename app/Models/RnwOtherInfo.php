<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RnwOtherInfo extends Model
{
    use HasFactory;

    protected $table = 'rnwotherinfo';

    protected $primaryKey = 'roiid';

    protected $fillable = [
        'rid',
        'grant',
        'talent',
        'expectation',
    ];

    // Define inverse relationship to account
    public function renewal()
    {
        return $this->belongsTo(renewal::class, 'rid', 'rid');
    }
}
