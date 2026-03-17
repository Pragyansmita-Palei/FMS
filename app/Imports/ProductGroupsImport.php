<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductGroupsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // MAIN PRODUCT NAME → ID
        $mainProduct = Product::where('name', trim($row['main_product']))->first();

        if (!$mainProduct) {
            // skip this row if main product not found
            return null;
        }

        // ADDON PRODUCT NAMES → IDS
        $addonIds = [];

        if (!empty($row['addon_products'])) {

            $addonNames = array_filter(
                array_map('trim', explode(',', $row['addon_products']))
            );

            $addonIds = Product::whereIn('name', $addonNames)
                ->pluck('id')
                ->toArray();
        }

        return ProductGroup::updateOrCreate(
            [
                'name' => $row['name'],
            ],
            [
                'main_product'   => $mainProduct->id,
                'addon_products' => $addonIds,
                'color'          => $row['color'] ?? null,
                'status'         => isset($row['status']) ? (int)$row['status'] : 0,
            ]
        );
    }
}
