<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelItinerary extends Model
{
    use HasFactory;

    protected $table = 'travel_itinerary';
    protected $primaryKey = 'travelID';
    protected $fillable = [
        'regularID'
    ];

    public function travelLocations()
    {
        return $this->hasMany(TravelLocation::class, 'travelID');
    }
}
