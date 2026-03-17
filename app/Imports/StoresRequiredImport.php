<?php

namespace App\Imports;

use App\Models\Store;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StoresRequiredImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Store([
            'store_code'       => $this->generateStoreCode(),

            'storename'        => $row['storename'],
            'phone'            => $row['phone'],

            'address_line1'    => $row['address_line1'],
            'city'             => $row['city'],
            'state'            => $row['state'],
            'pincode'          => $row['pincode'],

            'contact_name'     => $row['contact_name'],
            'contact_phone'    => $row['contact_phone'],
            'contact_email'    => $row['contact_email'],
        ]);
    }

    protected function generateStoreCode()
    {
        do {
            $code = 'ST' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (Store::where('store_code', $code)->exists());

        return $code;
    }

    public function rules(): array
    {
        return [
            '*.storename'        => ['required'],
            '*.phone'            => ['required'],
            '*.address_line1'    => ['required'],
            '*.city'             => ['required'],
            '*.state'            => ['required'],
            '*.pincode'          => ['required'],

            '*.contact_name'     => ['required'],
            '*.contact_phone'    => ['required'],
            '*.contact_email'    => ['required','email'],
        ];
    }
}
