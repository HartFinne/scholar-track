<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllowanceEvent extends Model
{
    use HasFactory;

    protected $table = 'allowanceevent';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'eventtype',
        'eventloc',
        'totalprice',
        'memo',
        'waiver',
        'acknowledgement',
        'liquidation'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
