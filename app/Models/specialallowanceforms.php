<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialAllowanceForms extends Model
{
    use HasFactory;

    protected $table = 'specialallowanceforms';

    protected $primaryKey = 'id';

    protected $fillable = [
        'filetype',
        'pathname',
    ];
}
