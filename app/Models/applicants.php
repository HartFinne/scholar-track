<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;

class applicants extends Authenticatable implements CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'applicants';

    protected $primaryKey = 'apid';

    protected $fillable = [
        'casecode',
        'password',
        'name',
        'chinesename',
        'age',
        'sex',
        'birthdate',
        'homeaddress',
        'region',
        'city',
        'barangay',
        'email',
        'phonenum',
        'occupation',
        'income',
        'fblink',
        'isIndigenous',
        'indigenousgroup',
        'applicationstatus',
        'prioritylevel',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function routeNotificationForMail($notification)
    {
        return $this->email;  // Use scEmail for notifications instead of emailq
    }


    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function familyinfo()
    {
        return $this->hasMany(apfamilyinfo::class, 'casecode', 'casecode');
    }

    public function educcollege()
    {
        return $this->hasOne(apceducation::class, 'casecode', 'casecode');
    }

    public function educelemhs()
    {
        return $this->hasOne(apeheducation::class, 'casecode', 'casecode');
    }

    public function otherinfo()
    {
        return $this->hasOne(apotherinfo::class, 'casecode', 'casecode');
    }

    public function requirements()
    {
        return $this->hasOne(aprequirements::class, 'casecode', 'casecode');
    }

    public function casedetails()
    {
        return $this->hasOne(apcasedetails::class, 'casecode', 'casecode');
    }

    public function progress()
    {
        return $this->hasMany(approgress::class, 'casecode', 'casecode');
    }
}
