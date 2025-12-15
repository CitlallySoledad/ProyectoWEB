<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class UpdateEventStatuses extends Command
{

    protected $signature = 'events:update-statuses';


    protected $description = 'Actualiza automáticamente los estados de los eventos basándose en sus fechas';


    public function handle()
    {
        $this->info('🔄 Actualizando estados de eventos...');

        $events = Event::whereNotNull('start_date')
            ->where('status', '!=', 'borrador') 
            ->get();

        $updated = 0;
        $now = now()->startOfDay();

        foreach ($events as $event) {
            $startDate = $event->start_date ? Carbon::parse($event->start_date)->startOfDay() : null;
            $endDate = $event->end_date ? Carbon::parse($event->end_date)->startOfDay() : null;
            
            $oldStatus = $event->getRawStatusAttribute();
            $newStatus = null;

            if ($endDate && $now->gt($endDate)) {
                $newStatus = 'cerrado';
            } elseif ($startDate && $now->gte($startDate)) {
                $newStatus = 'activo';
            } elseif ($startDate && $now->lt($startDate)) {
                $newStatus = 'publicado';
            }

            if ($newStatus && $oldStatus !== $newStatus) {
                \DB::table('events')
                    ->where('id', $event->id)
                    ->update(['status' => $newStatus, 'updated_at' => now()]);
                
                $this->line("✅ {$event->title}: {$oldStatus} → {$newStatus}");
                $updated++;
            }
        }

        if ($updated > 0) {
            $this->info("✨ Se actualizaron {$updated} eventos.");
        } else {
            $this->info("✓ Todos los eventos están al día.");
        }

        return Command::SUCCESS;
    }
}
