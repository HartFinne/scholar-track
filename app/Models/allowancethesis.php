<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowanceThesis extends Model
{
    use HasFactory;

    protected $table = 'allowancethesis';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'thesistitle',
        'totalprice',
        'titlepage',
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
