<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class aprequirements extends Model
{
    use HasFactory;

    protected $table = 'aprequirements';

    protected $primaryKey = 'aprid';

    protected $fillable = [
        'casecode',
        'idpic',
        'reportcard',
        'regiform',
        'autobio',
        'familypic',
        'houseinside',
        'houseoutside',
        'utilitybill',
        'sketchmap',
        'payslip',
        'indigencycert',
    ];

    public function applicant()
    {
        return $this->belongsTo(applicants::class, 'casecode', 'casecode');
    }
}
