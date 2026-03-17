<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductGroupsSampleExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'main_product',
            'addon_products',
            'color',
            'status',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Sample Group',
                'Sample Main Product Name',
                'Addon Product 1,Addon Product 2',
                '#ff0000',
                1
            ]
        ];
    }
}
