<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class allowanceproject extends Model
{
    use HasFactory;

    protected $table = 'allowanceproject';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'subject',
        'totalprice',
        'certification',
        'acknowledgement',
        'purchaseproof',
        'liquidation'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
