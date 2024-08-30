<?php

namespace App\Exports;

use App\Models\Overtime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OvertimeExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Overtime::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'user_id',
            'posisi',
            'pekerjaan',
            'tanggal',
            'jam_mulai',
            'jam_selesai',
            'total_jam',
            'ttd_pekerja',
            'ttd_manager',
            'created_at',
        ];
    }
}
