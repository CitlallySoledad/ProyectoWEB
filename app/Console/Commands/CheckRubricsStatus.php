<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rubric;
use App\Models\Project;
use App\Models\User;

class CheckRubricsStatus extends Command
{
    protected $signature = 'rubrics:status';
    protected $description = 'Check current rubrics and projects status for judge';

    public function handle()
    {
        $judge = User::where('name', 'Juez')->orWhere('email', 'judge@example.com')->first();
        
        if (!$judge) {
            $judge = User::whereHas('roles', function ($q) {
                $q->where('name', 'judge');
            })->first();
        }

        if (!$judge) {
            $judge = User::where('is_admin', 0)->first();
        }

        if (!$judge) {
            $this->error('No judge user found');
            return;
        }

        $this->info("=== JUDGE USER ===");
        $this->line("ID: " . $judge->id);
        $this->line("Name: " . $judge->name);
        $this->line("Email: " . $judge->email);

        $projects = Project::whereHas('judges', function ($q) use ($judge) {
            $q->where('users.id', $judge->id);
        })->get();

        $this->info("\n=== PROJECTS ASSIGNED TO JUDGE ===");
        $this->line("Total: " . $projects->count());
        foreach ($projects as $p) {
            $rubricName = $p->rubric_id ? $p->rubric?->name : 'NONE';
            $this->line("- " . $p->name . " | Rubric: " . $rubricName);
        }

        $rubrics = Rubric::all();
        $this->info("\n=== AVAILABLE RUBRICS ===");
        foreach ($rubrics as $r) {
            $status = $r->status === 'activa' ? '✓ ACTIVE' : '✗ INACTIVE';
            $this->line("- " . $r->name . " (ID: " . $r->id . ") | " . $status . " | Criteria: " . $r->criteria->count());
        }

        $this->info("\n=== SUMMARY ===");
        $this->line("Projects with rubric: " . $projects->where('rubric_id', '!=', null)->count());
        $this->line("Projects without rubric: " . $projects->where('rubric_id', null)->count());
    }
}
