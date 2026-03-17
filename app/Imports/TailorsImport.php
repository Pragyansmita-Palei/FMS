<?php

namespace App\Imports;

use App\Models\Tailor;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TailorsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            // get last number only once
            $lastTailor = Tailor::latest('id')->first();

            $lastNumber = 0;

            if ($lastTailor && $lastTailor->tailor_id) {
                $lastNumber = (int) str_replace('FMS-T-', '', $lastTailor->tailor_id);
            }

            foreach ($rows as $row) {

                if (
                    empty($row['name']) ||
                    empty($row['email']) ||
                    empty($row['password']) ||
                    empty($row['phone'])
                ) {
                    continue;
                }

                // skip if user already exists
                if (User::where('email', $row['email'])->exists()) {
                    continue;
                }

                // increment tailor number
                $lastNumber++;

                $tailorId = 'FMS-T-' . $lastNumber;

                // users table
                $user = User::create([
                    'name'     => $row['name'],
                    'email'    => $row['email'],
                    'password' => Hash::make($row['password']),
                ]);

                $user->assignRole('tailors');

                // tailors table
                Tailor::create([
                    'user_id'         => $user->id,
                    'tailor_id'       => $tailorId,
                    'phone'           => $row['phone'],
                    'alternate_phone' => $row['alternate_phone'] ?? null,
                    'address_line1'   => $row['address_line1'] ?? null,
                    'address_line2'   => $row['address_line2'] ?? null,
                    'city'            => $row['city'] ?? null,
                    'state'           => $row['state'] ?? null,
                    'pin'             => $row['pin'] ?? null,
                    'landmark'        => $row['landmark'] ?? null,
                ]);
            }

        });
    }
}

