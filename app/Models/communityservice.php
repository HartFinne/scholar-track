<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class communityservice extends Model
{
    use HasFactory;

    protected $table = 'communityservice';

    protected $primaryKey = 'csid';

    protected $fillable = [
        'title',
        'staffID',
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
