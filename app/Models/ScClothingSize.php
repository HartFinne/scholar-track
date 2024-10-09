<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScClothingSize extends Model
{
    use HasFactory;

    protected $table = 'sc_clothingsize';

    public $timestamps = false;


    protected $fillable = [
        'caseCode',
        'scTShirtSize',
        'scShoesSize',
        'scSlipperSize',
        'scPantsSize',
        'scJoggingPantSize'
    ];
}
