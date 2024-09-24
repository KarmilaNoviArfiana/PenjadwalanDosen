<?php

namespace App\Console\Commands;

use App\Helpers\WaSender;
use App\Models\Kegiatan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MyScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jadwal:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->reminder2();
        $this->reminder7();
    }

    public function reminder7() {
        
        Log::info("Cron job Berhasil di jalankan " . date('Y-m-d H:i:s'));
        $startTime = date("Y-m-d", strtotime("+7 days"));
        // tanggal lebih besar dari sekarang & kurang dari 7 hari dari sekarang
        $query = Kegiatan::where('tanggal', '<=', $startTime)
        ->where('tanggal', '>=', date('Y-m-d'))
        // minimal 2 menit setelah pembuatan baru direminder
        ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime('- 2 minutes')))
        // mencegah pengulangan reminder
        ->where('reminder', 0)
        ->where('reminder2', 0);
        Log::info(\Str::replaceArray('?', $query->getBindings(), $query->toSql()));
        $schedules = $query->get();
        Log::info($schedules);
        foreach ($schedules as $schedule) {
            try {
                WaSender::send($schedule->dosen->telp, 'Reminder: Terdapat kegiatan ' . $schedule->tugas . "
                \nTanggal: " . $schedule->tanggal .  "\nNama Kegiatan: " . $schedule->nama_kegiatan . " ");
                $payload=[];
                $payload['reminder'] = 1;
                Log::info($payload);
                $schedule->update($payload);
                Log::info("Berhasil");
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
        }
    }
    public function reminder2() {
        
        Log::info("Cron job Berhasil di jalankan " . date('Y-m-d H:i:s'));
        $startTime = date("Y-m-d", strtotime("+1 days"));
        // tanggal lebih besar dari sekarang & kurang dari 7 hari dari sekarang
        $query = Kegiatan::where('tanggal', '<=', $startTime)
        ->where('tanggal', '>=', date('Y-m-d'))
        // minimal 2 menit setelah pembuatan baru direminder
        ->where('created_at', '<=', date('Y-m-d H:i:s', strtotime('- 2 minutes')))
        // mencegah pengulangan reminder
        ->where('reminder2', 0);
        Log::info(\Str::replaceArray('?', $query->getBindings(), $query->toSql()));
        $schedules = $query->get();
        Log::info($schedules);
        foreach ($schedules as $schedule) {
            try {
                WaSender::send($schedule->dosen->telp, 'Reminder: Terdapat kegiatan ' . $schedule->tugas . "
                \nTanggal: " . $schedule->tanggal .  "\nNama Kegiatan: " . $schedule->nama_kegiatan . " ");
                $payload=[];
                $payload['reminder2'] = 1;
                Log::info($payload);
                $schedule->update($payload);
                Log::info("Berhasil");
            } catch (\Throwable $th) {
                Log::info($th->getMessage());
            }
        }
    }
}
