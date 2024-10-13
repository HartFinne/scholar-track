<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class humanitiesclass extends Model
{
    use HasFactory;

    protected $table = 'humanitiesclass';

    protected $fillable = [
        'image1',
        'image2',
        'image3',
        'topic',
        'hcdate',
        'hcstarttime',
        'hcendtime',
        'totalattendees'
    ];
}
