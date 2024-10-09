<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScAddressInfo extends Model
{
    use HasFactory;

    protected $table = 'sc_addressinfo';

    public $timestamps = false;


    protected $fillable = [
        'caseCode',
        'scArea',
        'scResidential',
        'scBarangay',
        'scCity',
        'scProvince',
        'scRegion',
        'scPermanent'
    ];

    // Define inverse relationship to account

}
