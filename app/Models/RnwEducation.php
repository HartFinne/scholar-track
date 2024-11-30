<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RnwEducation extends Model
{
    use HasFactory;

    protected $table = 'rnweducinfo';

    protected $primaryKey = 'reiid';

    protected $fillable = [
        'rid',
        'schoolyear',
        'gwa',
        'gwaconduct',
        'chinesegwa',
        'chinesegwaconduct',
    ];

    // Define inverse relationship to account
    public function renewal()
    {
        return $this->belongsTo(renewal::class, 'rid', 'rid');
    }
}
