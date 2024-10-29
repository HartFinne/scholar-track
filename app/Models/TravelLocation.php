<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelLocation extends Model
{
    use HasFactory;

    protected $table = 'travel_location';

    protected $primaryKey = 'locationID';

    protected $fillable = [
        'travelID',
        'travel_from',
        'travel_to',
        'estimated_time',
        'vehicle_type',
        'fare_rate',
    ];

    public function travelItinerary()
    {
        return $this->belongsTo(TravelItinerary::class, 'travelID');
    }
}
