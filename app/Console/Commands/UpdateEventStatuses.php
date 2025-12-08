<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class UpdateEventStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza automáticamente los estados de los eventos basándose en sus fechas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Actualizando estados de eventos...');

        $events = Event::whereNotNull('start_date')
            ->where('status', '!=', 'borrador') // No tocar borradores
            ->get();

        $updated = 0;
        $now = now()->startOfDay();

        foreach ($events as $event) {
            $startDate = $event->start_date ? Carbon::parse($event->start_date)->startOfDay() : null;
            $endDate = $event->end_date ? Carbon::parse($event->end_date)->startOfDay() : null;
            
            $oldStatus = $event->getRawStatusAttribute();
            $newStatus = null;

            // Determinar nuevo estado
            if ($endDate && $now->gt($endDate)) {
                $newStatus = 'cerrado';
            } elseif ($startDate && $now->gte($startDate)) {
                $newStatus = 'activo';
            } elseif ($startDate && $now->lt($startDate)) {
                $newStatus = 'publicado';
            }

            // Actualizar si cambió
            if ($newStatus && $oldStatus !== $newStatus) {
                // Actualizar directamente en la BD sin triggear el accessor
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
