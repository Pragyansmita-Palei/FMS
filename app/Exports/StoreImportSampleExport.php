<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class StoreImportSampleExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'storename',
            'phone',
            'address_line1',
            'city',
            'state',
            'pincode',
            'contact_name',
            'contact_phone',
            'contact_email',
        ];
    }
}
