<?php

namespace App\Imports;

use App\Models\Overtime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OvertimeImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Overtime([
            'user_id' => $row['user_id'],
            'posisi' => $row['posisi'],
            'pekerjaan' => $row['pekerjaan'],
            'tanggal' => $row['tanggal'],
            'jam_mulai' => $row['jam_mulai'],
            'jam_selesai' => $row['jam_selesai'],
            'jam_selesai' => $row['jam_selesai'],
            'total_jam' => $row['total_jam'],
        ]);
    }
}
