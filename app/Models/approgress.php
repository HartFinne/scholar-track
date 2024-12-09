<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class approgress extends Model
{
    use HasFactory;

    protected $table = 'approgress';

    protected $primaryKey = 'id';

    protected $fillable = [
        'casecode',
        'phase',
        'status',
        'remark',
        'msg',
    ];

    public function applicant()
    {
        return $this->belongsTo(applicants::class, 'casecode', 'casecode');
    }
}
