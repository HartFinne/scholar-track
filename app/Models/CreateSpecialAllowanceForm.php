<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreateSpecialAllowanceForm extends Model
{
    use HasFactory;

    protected $table = 'create_special_allowance_forms';

    protected $primaryKey = 'csafid';

    protected $fillable = [
        'formname',
        'formcode',
        'requestor',
        'instruction',
        'downloadablefiles',
        'database',
    ];

    public function formstructure()
    {
        return $this->hasMany(SpecialAllowanceFormStructure::class, 'csafid', 'csafid');
    }
}
