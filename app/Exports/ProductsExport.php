<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('store')->get()->map(function($product) {
            return [
                'ID' => $product->id,
                'Store' => $product->store->storename ?? '-',
                'Name' => $product->name,
                'Group' => $product->group_type,
                'MRP' => $product->mrp,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Store', 'Name', 'Group', 'MRP'];
    }
}
