<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialAllowanceSummary extends Model
{
    use HasFactory;

    protected $table = 'special_allowance_summaries';

    protected $primaryKey = 'id';

    protected $fillable = [
        'totalrequests',
        'pending',
        'completed',
        'accepted',
        'rejected',
    ];
}
