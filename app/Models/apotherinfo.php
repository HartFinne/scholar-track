<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apotherinfo extends Model
{
    use HasFactory;

    protected $table = 'apotherinfo';

    protected $primaryKey = 'apoid';

    protected $fillable = [
        'casecode',
        'grant',
        'talent',
        'expectations',
    ];

    public function applicant()
    {
        return $this->belongsTo(applicants::class, 'casecode', 'casecode');
    }
}
