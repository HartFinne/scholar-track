<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OjtTravelItinerary extends Model
{

    use HasFactory;

    protected $table = 'ojt_travel_itinerary';

    protected $primaryKey = 'ojtID';
    protected $fillable = [
        'regularID',
        'start_of_ojt',
        'end_of_ojt',
        'endorsement',
        'acceptance',
    ];

    public function ojtLocations()
    {
        return $this->hasMany(OjtLocation::class, 'ojtID');
    }
}
