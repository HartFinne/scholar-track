<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class csregistration extends Model
{
    use HasFactory;

    protected $table = 'csregistration';

    protected $primaryKey = 'csrid';

    protected $fillable = [
        'csid',
        'caseCode',
        'registatus'
    ];

    // Define inverse relationship to account
    public function user()
    {
        return $this->belongsTo(User::class, 'caseCode', 'caseCode');
    }
}
