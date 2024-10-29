<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Datasets extends Model
{
    use HasFactory;

    protected $table = 'datasets';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'startcontract',
        'endcontract',
        'gwasem1',
        'gwasem2',
        'cshours',
        'ltecount',
        'penaltycount'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
