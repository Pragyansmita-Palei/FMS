<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = Customer::withCount('projects');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return $query->latest()->get()->map(function ($c) {
            return [
                'Customer Code' => $c->customer_code,
                'Name'          => $c->name,
                'Phone'         => $c->phone,
                'Email'         => $c->email,
                'Orders'        => $c->projects_count,
                'Join Date'     => $c->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Customer Code',
            'Name',
            'Phone',
            'Email',
            'Orders',
            'Join Date',
        ];
    }
}
