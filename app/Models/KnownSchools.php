<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KnownSchools extends Model
{
    use HasFactory;

    protected $table = 'known_schools';

    protected $primaryKey = 'id';

    protected $fillable = [
        'schoolname',
        'academiccycle',
        'highestgwa'
    ];
}
