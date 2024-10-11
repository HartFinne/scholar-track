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
        'password',
        'scPhoneNum',
        'scStatus',
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

    public function basicInfo()
    {
        return $this->hasOne(ScBasicInfo::class, 'caseCode', 'caseCode');
    }

    public function education()
    {
        return $this->hasOne(ScEducation::class, 'caseCode', 'caseCode');
    }

    public function addressInfo()
    {
        return $this->hasOne(ScAddressInfo::class, 'caseCode', 'caseCode');
    }
    public function clothingSize()
    {
        return $this->hasOne(ScClothingSize::class, 'caseCode', 'caseCode');
    }

    public function penalty()
    {
        return $this->hasOne(ScPenalty::class, 'caseCode', 'caseCode');
    }
}
