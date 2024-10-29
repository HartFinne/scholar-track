<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class allowancetranspo extends Model
{
    use HasFactory;

    protected $table = 'allowancetranspo';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'totalprice',
        'purpose',
        'staffname',
        'transpoform'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
