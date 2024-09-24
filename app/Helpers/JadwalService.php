<?php

namespace App\Helpers;

use App\Models\Jadwal;
use App\Models\Kegiatan;

class JadwalService
{
  public static function checkTabrakan($nip, $tanggal, $waktuMulai, $waktuSelesai)
  {

    $exist = Jadwal::where(['tanggal' => $tanggal, 'nip' => $nip])
      ->where(function ($qb) use ($tanggal, $waktuMulai, $waktuSelesai) {
        $startTime = $tanggal . " " . $waktuMulai;
        $endTime = $tanggal . " " . $waktuSelesai;
        $qb->where(function ($qb) use ($startTime, $endTime) {
          $qb->whereBetween('waktu_mulai', [$startTime, $endTime])
            ->orWhereBetween('waktu_selesai', [$startTime, $endTime]);
        });
      })->exists();

    if ($exist) return true;


    $exist = Kegiatan::where(['tanggal' => $tanggal, 'nip' => $nip])
      ->where(function ($qb) use ($tanggal, $waktuMulai, $waktuSelesai) {
        $startTime = $tanggal . " " . $waktuMulai;
        $endTime = $tanggal . " " . $waktuSelesai;
        $qb->where(function ($qb) use ($startTime, $endTime) {
          $qb->whereBetween('waktu_mulai', [$startTime, $endTime])
            ->orWhereBetween('waktu_selesai', [$startTime, $endTime]);
        });
      })->exists();

    if ($exist) return true;


    return false;
  }
}
