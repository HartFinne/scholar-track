<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class humanitiesclass extends Model
{
    use HasFactory;

    protected $table = 'humanitiesclass';

    protected $primaryKey = 'hcid';

    protected $fillable = [
        'topic',
        'hcdate',
        'hclocation',
        'hcstarttime',
        'hcendtime',
        'totalattendees'
    ];

    public function hcattendance()
    {
        return $this->hasMany(hcattendance::class, 'hcid', 'hcid');
    }
}
