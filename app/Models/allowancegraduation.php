<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class allowancegraduation extends Model
{
    use HasFactory;

    protected $table = 'allowancegraduation';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'totalprice',
        'listofgraduates',
        'acknowledgement',
        'liquidation'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
