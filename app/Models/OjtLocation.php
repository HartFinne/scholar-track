<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OjtLocation extends Model
{
    use HasFactory;

    protected $table = 'ojt_location';

    protected $primaryKey = 'ojtLocationID';

    protected $fillable = [
        'ojtID',
        'ojt_from',
        'ojt_to',
        'ojt_estimated_time',
        'ojt_vehicle_type',
        'ojt_fare_rate',
    ];

    public function ojtTravelItinerary()
    {
        return $this->belongsTo(OjtTravelItinerary::class, 'ojtID');
    }
}
