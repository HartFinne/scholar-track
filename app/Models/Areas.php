<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Areas extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'areaname',
        'areacode',
    ];
}
