<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rubric;
use App\Models\Project;
use App\Models\User;

class ApplyRubricToProjects extends Command
{
    protected $signature = 'rubrics:apply-to-judge {rubric_id}';
    protected $description = 'Apply a rubric to all projects assigned to the current judge';

    public function handle()
    {
        $rubricId = $this->argument('rubric_id');
        $rubric = Rubric::find($rubricId);

        if (!$rubric) {
            $this->error("Rubric with ID {$rubricId} not found");
            return;
        }

        if ($rubric->status === 'inactiva') {
            $this->error("Cannot apply an inactive rubric");
            return;
        }

        $judge = User::where('name', 'Juez Demo')->first() ?: User::where('is_admin', 0)->first();

        if (!$judge) {
            $this->error('No judge user found');
            return;
        }

        $projects = Project::whereHas('judges', function ($q) use ($judge) {
            $q->where('users.id', $judge->id);
        })->get();

        if ($rubric->event_id) {
            $projects = $projects->filter(function ($p) use ($rubric) {
                return $p->event_id === $rubric->event_id;
            });
        }

        $count = 0;
        foreach ($projects as $project) {
            $project->rubric_id = $rubric->id;
            $project->save();
            $count++;
        }

        $this->info("✓ Rubric '{$rubric->name}' applied to {$count} project(s)");
    }
}
