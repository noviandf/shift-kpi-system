<?php

namespace App\Imports;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class ScheduleImport implements ToCollection
{
    protected $bulan;
    protected $tahun;

    // Menangkap input bulan dan tahun dari form UI
    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection(Collection $rows)
    {
        // Index 1 (Baris ke-2 di Excel) adalah baris tanggal: ,,,1-Jan,2-Jan,...
        $barisTanggal = $rows[1];

        $shiftValid = ['P', 'P3', 'MD', 'ML', 'CT', 'OFF', 'I/S'];

        // Looping mulai dari Index 2 (Baris ke-3 tempat data agen dimulai)
        for ($i = 2; $i < count($rows); $i++) {
            $row = $rows[$i];

            // ---------------------------------------------------------
            // FILTER KRITIS: CEK KOLOM NOMOR (INDEX 0)
            // Jika kolom NO kosong, ATAU bukan angka (misal teks "Keterangan"), 
            // maka lewati baris ini. Ini mencegah sampah CSV masuk ke Database.
            // ---------------------------------------------------------
            if (empty($row[0]) || !is_numeric(trim($row[0]))) {
                continue;
            }

            // Pastikan kolom nama (Index 1) tidak kosong
            if (empty($row[1])) {
                continue;
            }

            // Smart Import: Buat Akun jika nama belum ada
            $namaAgen = trim($row[1]);
            $emailDummy = strtolower(str_replace(' ', '.', $namaAgen)) . '@perusahaan.com';

            $user = User::firstOrCreate(
                ['name' => $namaAgen],
                [
                    'email'    => $emailDummy,
                    'password' => bcrypt('password123'),
                    'role'     => 'agent'
                ]
            );

            // Looping jadwal ke samping (Kolom index 3 sampai 33)
            for ($col = 3; $col <= 33; $col++) {

                if (!empty($barisTanggal[$col]) && !empty($row[$col])) {

                    $kodeShift = strtoupper(trim($row[$col]));

                    if (in_array($kodeShift, $shiftValid)) {

                        // Ekstrak angka "1" dari teks "1-Jan"
                        $hari = explode('-', $barisTanggal[$col])[0];

                        // Validasi angka tanggal
                        if (is_numeric($hari)) {
                            $tanggalValid = Carbon::createFromDate($this->tahun, $this->bulan, (int)$hari)->format('Y-m-d');

                            Schedule::updateOrCreate(
                                [
                                    'user_id'    => $user->id,
                                    'shift_date' => $tanggalValid,
                                ],
                                [
                                    'shift_type' => $kodeShift,
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
