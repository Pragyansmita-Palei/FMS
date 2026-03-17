<?php

namespace App\Exports;

use App\Models\Store;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StoresExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Store::select(
            'storename',
            'phone',
            'email',
            'address_line1'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Store Name',
            'Phone',
            'Email',
            'Address'
        ];
    }
}
