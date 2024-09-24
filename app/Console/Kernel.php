<?php

namespace App\Console;

use App\Helpers\WaSender;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Kegiatan;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $now = now()->toDateString();
            Kegiatan::where('tanggal', '<', $now)
                ->update(['status_kehadiran' => 'Hadir']);
        })->daily(); // Pengecekan dilakukan setiap hari

        // buat schedulernya, ini nanti samean coba mba
        // kalau ga bisa ta benerin lagi
        // soalnya belum ta test yang ini di localku
        //oiya mas yg di menu kegiatan dosen jg sama kan mas, kalo di menu jadwal dan di menuk kegiatan ada kegiatan maka gabisa ditambahin?

        // udah mba
        // saya buat 1 service buat pengecekannya

        // yang pertama masuk karena tanggalnya 30, nggak ada jadwal
        // yang ke 2 gagal karena tanggal 1 jam itu, ada jadwal 

        //oke mas, untuk yg reminder ini nyobanya gimana ya mas?
        //atau saya tamabah kegiatan dulu sebelum 7 hari ya mas?
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
