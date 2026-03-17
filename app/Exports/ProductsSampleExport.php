<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsSampleExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            [
                'store_name'   => 'SuperMart',        // must match storename
                'name'         => 'Chocolate',
                'group_type'   => 'Tailoring',
                'selling_unit' => 'Loading',
                'quantity'     => 5,
                'mrp'          => 1000,
                'discount'     => 10,
                'tax_rate'     => 12,
                'description'  => 'Sample product'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'store_name',
            'name',
            'group_type',
            'selling_unit',
            'quantity',
            'mrp',
            'discount',
            'tax_rate',
            'description'
        ];
    }
}
