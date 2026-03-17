<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TailorsSampleExport implements FromArray, WithHeadings
{
  public function headings(): array
{
    return [
        'name',
        'email',
        'password',
        'phone',
        'alternate_phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pin',
        'landmark',
    ];
}

public function array(): array
{
    return [
        [
            'John Doe',
            'john@example.com',
            'Admin@123',
            '9876543210',
            '',
            'Street 1',
            '',
            'Mumbai',
            'Maharashtra',
            '400001',
            '',
        ]
    ];
}

}
