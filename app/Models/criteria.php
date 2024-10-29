<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = 'criteria';

    protected $primaryKey = 'crid';

    protected $fillable = [
        'cgwa',
        'shsgwa',
        'jhsgwa',
        'elemgwa',
        'fincome',
        'mincome',
        'sincome',
        'aincome',
    ];
}
