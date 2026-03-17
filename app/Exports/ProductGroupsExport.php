<?php

namespace App\Exports;

use App\Models\ProductGroup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductGroupsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return ProductGroup::with('mainProduct')->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'main_product',
            'addon_products',

        ];
    }

    public function map($group): array
    {
        // fetch addon product names
        $addonNames = [];

        if (!empty($group->addon_products)) {
            $addonNames = \App\Models\Product::whereIn('id', $group->addon_products)
                ->pluck('name')
                ->toArray();
        }

        return [
            $group->name,
            optional($group->mainProduct)->name,   // product name
            implode(',', $addonNames),              // product names
         
        ];
    }
}
