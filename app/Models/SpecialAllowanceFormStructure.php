<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecialAllowanceFormStructure extends Model
{
    use HasFactory;

    protected $table = 'special_allowance_forms_structure';

    protected $primaryKey = 'csafid';

    protected $fillable = [
        'csafid',
        'fieldname',
        'fieldtype',
    ];

    public function saForm()
    {
        return $this->belongsTo(CreateSpecialAllowanceForm::class, 'csafid', 'csafid');
    }
}
