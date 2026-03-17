<?php

namespace App\Exports;

use App\Models\SalesAssociate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesAssociatesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = SalesAssociate::with('user');

        if ($search = $this->request->search) {

            $query->where(function ($q) use ($search) {

                $q->where('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });

            });
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Sales ID',
            'Name',
            'Email',
            'Phone',
            'Alternate Phone',
            'City',
            'State',
            'Pin',
        ];
    }

    public function map($sa): array
    {
        return [
            $sa->sales_id,
            $sa->user?->name,
            $sa->user?->email,
            $sa->phone,
            $sa->alternate_phone,
            $sa->city,
            $sa->state,
            $sa->pin,
        ];
    }
}
