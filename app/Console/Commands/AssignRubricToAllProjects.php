<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;

class AssignRubricToAllProjects extends Command
{
    protected $signature = 'projects:assign-rubric {rubric_id}';
    protected $description = 'Assign a rubric to all projects without one';

    public function handle()
    {
        $rubricId = $this->argument('rubric_id');

        $projects = Project::whereNull('rubric_id')->get();

        if ($projects->isEmpty()) {
            $this->info('All projects already have a rubric assigned');
            return;
        }

        foreach ($projects as $project) {
            $project->rubric_id = $rubricId;
            $project->save();
            $this->line("✓ Assigned rubric to: {$project->name}");
        }

        $this->info("✓ Total projects updated: {$projects->count()}");
    }
}
