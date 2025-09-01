<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo user if none exists
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create demo projects
        $project1 = Project::create([
            'name' => 'Website Redesign',
            'description' => 'Complete redesign of the company website with modern UI/UX principles.',
            'is_active' => true,
            'user_id' => $user->id,
        ]);

        $project2 = Project::create([
            'name' => 'Mobile App Development',
            'description' => 'Development of iOS and Android mobile applications.',
            'is_active' => true,
            'user_id' => $user->id,
        ]);

        $project3 = Project::create([
            'name' => 'API Refactoring',
            'description' => 'Refactor legacy API endpoints for better performance and maintainability.',
            'is_active' => false,
            'user_id' => $user->id,
        ]);

        // Create demo tickets
        Ticket::create([
            'title' => 'Design new homepage layout',
            'description' => 'Create a modern, responsive homepage layout that follows our brand guidelines.',
            'status' => 'open',
            'priority' => 'high',
            'project_id' => $project1->id,
            'user_id' => $user->id,
            'due_date' => now()->addDays(7),
        ]);

        Ticket::create([
            'title' => 'Implement user authentication',
            'description' => 'Add user login/registration functionality with social media integration.',
            'status' => 'in_progress',
            'priority' => 'urgent',
            'project_id' => $project1->id,
            'user_id' => $user->id,
            'assigned_to' => $user->id,
            'due_date' => now()->addDays(3),
        ]);

        Ticket::create([
            'title' => 'Setup development environment',
            'description' => 'Configure development environment for React Native development.',
            'status' => 'resolved',
            'priority' => 'medium',
            'project_id' => $project2->id,
            'user_id' => $user->id,
            'assigned_to' => $user->id,
        ]);

        Ticket::create([
            'title' => 'Performance optimization',
            'description' => 'Optimize API response times and database queries.',
            'status' => 'open',
            'priority' => 'low',
            'project_id' => $project3->id,
            'user_id' => $user->id,
            'due_date' => now()->addDays(14),
        ]);
    }
}
