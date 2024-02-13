<?php

namespace App\Console;

use App\Models\Consultation;
use App\Mail\RappelConsultation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Envoyer les rappels par e-mail pour les consultations prévues dans la prochaine heure
            Consultation::whereBetween('rappel_at', [now(), now()->addHour()])
                ->get()
                ->each(function ($consultation) {
                    // Envoyer l'e-mail de rappel avec Mail::to
                    Mail::to($consultation->user->email)->send(new RappelConsultation($consultation));
                });
        })->hourly();
     }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require_once base_path('routes/console.php');
    }
}
