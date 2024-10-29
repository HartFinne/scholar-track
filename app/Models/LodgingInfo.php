<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LodgingInfo extends Model
{
    use HasFactory;

    protected $table = 'lodging_info';

    protected $primaryKey = 'lodgingID';

    protected $fillable = [
        'regularID',
        'address',
        'name_owner',
        'contact_no_owner',
        'monthly_rent',
        'lodging_type',
    ];

    public function regularAllowance()
    {
        return $this->belongsTo(RegularAllowance::class, 'regularID');
    }
}
