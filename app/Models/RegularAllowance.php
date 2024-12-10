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
        'caseCode',
        'schoolyear',
        'semester',
        'start_of_semester',
        'end_of_semester',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }

    public function education()
    {
        return $this->belongsTo(ScEducation::class, 'caseCode', 'caseCode');
    }

    public function basicInfo()
    {
        return $this->belongsTo(ScBasicInfo::class, 'caseCode', 'caseCode');
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
