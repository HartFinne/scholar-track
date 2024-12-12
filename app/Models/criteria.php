<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class criteria extends Model
{
    use HasFactory;

    protected $table = 'criteria';

    protected $primaryKey = 'crid';

    protected $fillable = []; // Default to an empty array

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Dynamically set the $fillable array if it's empty
        if (empty($this->fillable)) {
            $this->fillable = Schema::getColumnListing($this->table);
        }
    }
}
