<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Store;
use App\Models\GroupType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        /**
         * STEP 1: Find store by storename
         * (NO INSERT, ONLY FETCH ID)
         */
        $store = Store::where('storename', $row['store_name'])->first();

        if (!$store) {
            return null; // skip row if store not found
        }

        /**
         * STEP 2: Find group type
         */
        $group = GroupType::where('name', $row['group_type'])->first();
        if (!$group) {
            return null;
        }

        /**
         * STEP 3: Calculate price
         */
        $mrp = $row['mrp'] ?? 0;
        $discount = $row['discount'] ?? 0;
        $totalPrice = $mrp - ($mrp * $discount / 100);

        /**
         * STEP 4: Insert ONLY into products table
         */
        return new Product([
            'store_id'     => $store->id,          // from stores table
            'name'         => $row['name'],
            'group_type'   => $group->name,
            'selling_unit' => $row['selling_unit'],
            'quantity'     => $row['quantity'] ?? 0,
            'description'  => $row['description'] ?? '',
            'mrp'          => $mrp,
            'discount'     => $discount,
            'tax_rate'     => $row['tax_rate'] ?? 0,
            'total_price'  => round($totalPrice, 2),
        ]);
    }
}
