<?php

namespace App\Exports;

use App\Models\Tailor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TailorsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Tailor::with('user')->latest()->get();
    }

    public function map($tailor): array
    {
        return [
            $tailor->tailor_id,
            $tailor->user->name ?? '',
            $tailor->user->email ?? '',
            $tailor->phone,
            $tailor->city,
            $tailor->state,
            $tailor->pin,
        ];
    }

    public function headings(): array
    {
        return [
            'tailor_id',
            'name',
            'email',
            'phone',
            'city',
            'state',
            'pin',
        ];
    }
}
