<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpecialAllowanceFormExport implements FromArray, WithHeadings
{
    protected $formcode;
    protected $fieldnames;

    public function __construct($formcode, $fieldnames)
    {
        $this->formcode = $formcode;
        $this->fieldnames = $fieldnames;
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        // The first column is 'id', then 'caseCode', followed by the fieldnames
        return array_merge(['id', 'caseCode', 'requestType', 'requestDate', 'releaseDate', 'requestStatus'], $this->fieldnames);
    }

    // Return an empty array as we only want the headers
    public function array(): array
    {
        // No data rows, only headers should be shown
        return [];
    }
}
