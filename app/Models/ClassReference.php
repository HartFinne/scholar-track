<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassReference extends Model
{
    use HasFactory;
    protected $table = 'class_reference';
    protected $primaryKey = 'classID';

    protected $fillable = [
        'regularID',
        'registration_form',
    ];

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'classID', 'classID');
    }

    public function regular()
    {
        return $this->belongsTo(RegularAllowance::class, 'regularID', 'regularID');
    }
}
