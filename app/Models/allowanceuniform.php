<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class allowanceuniform extends Model
{
    use HasFactory;

    protected $table = 'allowanceuniform';

    protected $primaryKey = 'id';

    protected $fillable = [
        'caseCode',
        'uniformtype',
        'totalprice',
        'certificate',
        'acknowledgement',
        'uniformpic',
        'liquidation'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
