# Copilot Instructions - Hackathon Evaluation Platform

## Project Overview
Laravel 12 application for managing hackathon events, team collaboration, project submissions, and judge evaluations using rubric-based scoring. Uses SQLite (default), Spatie Permissions for role-based access, and Tailwind CSS v4 with Vite.

## Architecture & Key Components

### Role System (Spatie Permission Package)
- **Three roles**: `admin`, `judge`, `student` (not 'participant')
- Users get roles via `User::assignRole('student')` after seeding with `RolePermissionSeeder`
- Routes use `middleware(['auth', 'role:student'])` pattern (see `bootstrap/app.php` for alias registration)
- Models use `HasRoles` trait: `User` model includes `use Spatie\Permission\Traits\HasRoles;`
- Check roles: `Auth::user()->hasRole('admin')` or `$user->hasRole('judge')`

### Domain Models & Relationships
**Team Management** (pivot-based, not JSON):
- `Team` has `leader_id` (User) + many-to-many `members()` via `team_user` pivot with optional `role` column
- Leader is NOT automatically in pivot - explicitly attach: `$team->members()->attach($userId, ['role' => null])`
- `TeamJoinRequest` for student requests to join (status: pending/accepted/rejected)
- `TeamInvitation` with unique token for leader-initiated invites sent via email (`TeamInvitationMail`)

**Evaluation Workflow**:
- `Project` belongs to `Team`, `Event`, and `Rubric`; has many `judges()` via `project_judge` pivot
- `Rubric` has many `RubricCriterion` (each with weight/min_score/max_score)
- Judge creates `Evaluation` with many `EvaluationScore` (one per criterion)
- `EvaluationEvidence` stores file uploads during evaluation
- Status progression: `null` → `pendiente` → `completada`

### Route Organization
- **Public**: `/`, `/login`, `/registro`, `/invitacion/{token}` (no auth)
- **Student**: `/panel/*` routes (`role:student` middleware) - team management, profile, submissions
- **Admin**: `/admin/*` prefix (`role:admin`) - CRUD for teams, events, evaluations, users
- **Judge**: `/judge/*` prefix (`role:judge`) - view assigned projects, create evaluations with rubrics

Helper function in `routes/web.php`: `getRedirectRouteByRole($user)` centralizes post-login routing

## Development Workflow

### Setup Commands (from composer.json scripts)
```bash
composer setup              # Full setup: install, .env copy, key:gen, migrate, npm install+build
composer dev                # Run server + queue + vite concurrently
composer test               # Clear config and run Pest tests
```

### Database
- **Default**: SQLite at `database/database.sqlite`
- Run seeders in order: `RolePermissionSeeder` (roles first!), then `AdminUserSeeder`, `UserSeeder`
- Key migrations: teams→events→projects→evaluations→rubrics→pivot tables (`team_user`, `project_judge`)

### Testing
- Uses **Pest 4** (not PHPUnit syntax)
- `tests/Pest.php` extends `Tests\TestCase::class`
- RefreshDatabase commented out by default - uncomment in `Pest.php` if needed

## Project-Specific Patterns

### View Layouts
Three Blade layouts in `resources/views/layouts/`:
- `admin-panel.blade.php` (admin dashboard)
- `judge-panel.blade.php` (judge interface)
- `admin.blade.php` (legacy/shared layout)

### Team Capacity Logic
Teams limited to **4 members** including pending join requests:
```php
$memberCount = $team->members()->count();
$pendingCount = $team->joinRequests()->where('status', 'pending')->count();
if ($memberCount + $pendingCount >= 4) { /* reject */ }
```

### Team Role Assignment
Team invitations include role selection (Back-end, Front-end, Diseñador):
- Leader selects role when sending invitation via `/panel/mi-equipo`
- Role stored in `team_invitations.role` column (nullable varchar)
- Role displayed in invitation email (`resources/views/emails/team-invitation.blade.php`)
- When invitation accepted, role assigned in `team_user.role` pivot column
- Validation: `'role' => ['required', 'in:Back,Front,Diseñador']`

### Evaluation Score Calculation
Judges fill `EvaluationScore` per criterion; total calculated from rubric criterion weights:
```php
EvaluationScore::updateOrCreate(
    ['evaluation_id' => $evaluation->id, 'rubric_criterion_id' => $criterionId],
    ['score' => $score, 'comment' => $comment]
);
```

### Mail Configuration
- **Production mailer**: `smtp` (Gmail) - configured for real email sending
- **Development mailer**: `log` (check `storage/logs/laravel.log` for sent emails)
- Team invitations use tokenized links: `route('team-invitation.accept', ['token' => $invitation->token])`
- Gmail SMTP: `smtp.gmail.com:587` with TLS encryption
- Uses App Password (16-char) from Google Account security settings

## Key Files Reference
- **Auth flow**: `routes/web.php` lines 26-59 (unified login + role redirect)
- **Team controller**: `app/Http/Controllers/ParticipantTeamController.php` (join/invite/create logic)
- **Judge evaluation**: `app/Http/Controllers/Judge/EvaluationController.php` (rubric-based scoring)
- **Role seeder**: `database/seeders/RolePermissionSeeder.php` (defines all permissions)
- **Middleware registration**: `bootstrap/app.php` (Spatie + custom middleware)

## Common Pitfalls
1. **Don't forget Spatie cache**: Call `php artisan permission:cache-reset` after role/permission changes
2. **Team member attachment**: Leader must be manually added to pivot table after team creation
3. **Role names**: Use `student` not `participant` in code (views may say "participante" in Spanish)
4. **Vite assets**: Run `npm run dev` or files won't load; uses Tailwind v4 with Vite plugin
5. **Email sending**: Configured with Gmail SMTP - emails send immediately (synchronous, no queue)
6. **Gmail limits**: ~500 emails/day - monitor usage for bulk invitations

## Debugging Tools
- `tools/check_rubrics_status.php` - Verify rubric assignments
- `tools/create_test_evaluation.php` - Generate sample evaluation data
- `tools/db_dump.php` - Export database state
- `scripts/check_judge_user.php` - Verify judge user configuration
