<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'caseCode',
        'scEmail',
        'scPhoneNum',
        'password',
        'scStatus',
    ];

    // Instruct Laravel to use scEmail for notifications
    public function routeNotificationForMail($notification)
    {
        return $this->scEmail;  // Use scEmail for notifications instead of emailq
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scholarshipinfo()
    {
        return $this->hasOne(scholarshipinfo::class, 'caseCode', 'caseCode');
    }

    public function basicinfo()
    {
        return $this->hasOne(ScBasicInfo::class, 'caseCode', 'caseCode');
    }

    public function education()
    {
        return $this->hasOne(ScEducation::class, 'caseCode', 'caseCode');
    }

    public function grades()
    {
        return $this->hasMany(grades::class, 'caseCode', 'caseCode');
    }

    public function addressinfo()
    {
        return $this->hasOne(ScAddressInfo::class, 'caseCode', 'caseCode');
    }

    public function clothingsize()
    {
        return $this->hasOne(ScClothingSize::class, 'caseCode', 'caseCode');
    }

    public function csattendance()
    {
        return $this->hasMany(csattendance::class, 'caseCode', 'caseCode');
    }

    public function csregistration()
    {
        return $this->hasMany(csregistration::class, 'caseCode', 'caseCode');
    }

    public function hcattendance()
    {
        return $this->hasMany(hcattendance::class, 'caseCode', 'caseCode');
    }

    public function lte()
    {
        return $this->hasMany(lte::class, 'caseCode', 'caseCode');
    }

    public function penalty()
    {
        return $this->hasMany(penalty::class, 'caseCode', 'caseCode');
    }

    public function renewal()
    {
        return $this->belongsTo(renewal::class, 'caseCode', 'caseCode');
    }
}
