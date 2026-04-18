<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // 1. Izinkan kolom ini diisi form
    protected $fillable = ['user_id', 'shift_date', 'shift_type'];

    // 2. Buat relasi: Jadwal ini milik User (Agen) siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
