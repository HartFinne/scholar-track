<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Email;

class EmailsImport implements ToCollection, ToModel
{
    use Importable;

    private $current = 0;

    /**
     * Handle the imported data as a collection.
     *
     * @param Collection $collection
     */
    public function collection(Collection $collection) {}

    /**
     * Build a model instance from a row of the imported file.
     *
     * @param array $row
     * @return Email|null
     */
    public function model(array $row)
    {
        $this->current++;
        if ($this->current > 1) {
            $email = new Email;
            $email->email = $row[0];
            $email->save();
        }
    }
}
