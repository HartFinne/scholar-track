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
        return array_merge(['id', 'caseCode'], $this->fieldnames);
    }

    // Populate the Excel file with data (initially just with IDs and caseCodes)
    public function array(): array
    {
        // Generate sample data; just incrementing IDs and adding placeholder caseCodes
        $rows = [];
        for ($i = 1; $i <= 100; $i++) { // You can set the number of rows as needed
            // Empty values for fields
            $row = array_fill(0, count($this->fieldnames), '');

            // Add the auto-incrementing 'id' to the beginning
            array_unshift($row, $i);

            // Add a placeholder 'caseCode' after the 'id'
            array_splice($row, 1, 0, 'CaseCode_' . $i); // Insert 'caseCode' at position 1 (after 'id')

            // Add the row to the results
            $rows[] = $row;
        }

        return $rows;
    }
}
