<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScClothingSize extends Model
{
    use HasFactory;

    protected $table = 'sc_clothingsize';

    protected $primaryKey = 'cid';

    protected $fillable = [
        'caseCode',
        'scTShirtSize',
        'scShoesSize',
        'scSlipperSize',
        'scPantsSize',
        'scJoggingPantSize'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
