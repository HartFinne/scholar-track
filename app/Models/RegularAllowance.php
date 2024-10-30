<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularAllowance extends Model
{
    use HasFactory;

    protected $table = 'regular_allowance';
    protected $primaryKey = 'regularID';
    protected $fillable = [
        'gid',
        'start_of_semester',
        'end_of_semester',
        'status',
    ];

    public function grades()
    {
        return $this->belongsTo(grades::class, 'gid', 'gid');
    }

    public function classReference()
    {
        return $this->hasOne(ClassReference::class, 'regularID', 'regularID');
    }

    public function travelItinerary()
    {
        return $this->hasOne(TravelItinerary::class, 'regularID', 'regularID');
    }

    public function lodgingInfo()
    {
        return $this->hasOne(LodgingInfo::class, 'regularID', 'regularID');
    }

    public function ojtTravelItinerary()
    {
        return $this->hasOne(OjtTravelItinerary::class, 'regularID', 'regularID');
    }
}
