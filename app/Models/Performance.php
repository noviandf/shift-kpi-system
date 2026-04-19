<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;

    // Izinkan kolom-kolom ini diisi
    protected $fillable = [
        'user_id', 'supervisor_id', 'evaluation_month', 
        'aht_raw', 'qa_raw', 'attendance_raw', 
        'final_score', 'grade'
    ];

    // Relasi ke Agen yang dinilai
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Supervisor yang menilai
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}