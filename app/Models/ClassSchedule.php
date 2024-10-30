<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;



    protected $table = 'class_schedule';
    protected $primaryKey = 'classSchedID';
    protected $fillable = [
        'classID',
        'time_slot',
        'mon',
        'tue',
        'wed',
        'thu',
        'fri',
        'sat',
        'sun',
    ];

    public function classReference()
    {
        return $this->belongsTo(ClassReference::class, 'classID', 'classID');
    }
}
