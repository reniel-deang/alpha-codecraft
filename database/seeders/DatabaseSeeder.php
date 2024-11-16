<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use TeamTeaTime\Forum\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'user_type' => 'Admin',
            'password' => 'admin'
        ]);

        $categories = [
            'Android Development', 
            'Back-end Programming', 
            'Front-end Programming', 
            'Full Stack Development'
        ];

        $descriptions = [
            'Android development is the process of creating applications for devices running an Android operating system',
            'Back-end programming focuses on server-side development, managing databases, and ensuring efficient data flow between the server and client.',
            'Front-end programming involves designing and building the visual and interactive elements of software that users engage with directly.',
            'Full stack development involves working on both the front-end and back-end of applications, encompassing everything from user interfaces to server-side logic and database management.'
        ];

        $colors = [
            '#22c55e',
            '#0ea5e9',
            '#eab308',
            '#ef4444'
        ];

        foreach ($categories as $index => $category) {
            Category::create([
                'title' => $category,
                'description' => $descriptions[$index],
                'accepts_threads' => true,
                'color_light_mode' => $colors[$index],
                'color_dark_mode' => $colors[$index]
            ]);
        }
    }
}
