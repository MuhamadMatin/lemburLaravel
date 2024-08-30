<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'posisi',
        'pekerjaan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'total_jam',
        'ttd_pekerja',
        'ttd_manager',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
