<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class allowancebook extends Model
{
    use HasFactory;

    protected $table = 'allowancebook';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'booktitle',
        'author',
        'price',
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
