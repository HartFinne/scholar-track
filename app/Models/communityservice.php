<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityService extends Model
{
    use HasFactory;

    protected $table = 'communityservice';

    protected $primaryKey = 'csid';

    protected $fillable = [
        'title',
        'eventloc',
        'eventdate',
        'meetingplace',
        'calltime',
        'starttime',
        'facilitator',
        'slotnum',
        'volunteersnum',
        'eventstatus'
    ];

    public function csattendance()
    {
        return $this->hasMany(csattendance::class, 'csid', 'csid');
    }
}
