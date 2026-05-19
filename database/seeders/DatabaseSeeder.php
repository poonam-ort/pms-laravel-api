<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@pms.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        $member = User::updateOrCreate(
            ['email' => 'user@pms.test'],
            [
                'name' => 'Member User',
                'password' => Hash::make('password'),
                'role' => User::ROLE_USER,
            ]
        );

        $project = Project::updateOrCreate(
            ['name' => 'Website Redesign'],
            [
                'description' => 'Redesign company website',
                'created_by' => $admin->id,
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Design homepage mockup', 'project_id' => $project->id],
            [
                'description' => 'Create Figma mockups for homepage',
                'status' => Task::STATUS_IN_PROGRESS,
                'priority' => Task::PRIORITY_HIGH,
                'due_date' => now()->addDays(7),
                'assigned_to' => $member->id,
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Overdue sample task', 'project_id' => $project->id],
            [
                'description' => 'Past due — Django will mark as overdue',
                'status' => Task::STATUS_TODO,
                'priority' => Task::PRIORITY_MEDIUM,
                'due_date' => now()->subDays(3),
                'assigned_to' => $member->id,
            ]
        );
    }
}
