<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penalty extends Model
{
    use HasFactory;

    protected $table = 'penalty';

    protected $fillable = [
        'caseCode',
        'condition',
        'remark',
        'dateofpenalty'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
