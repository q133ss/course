<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        collect([
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
            [
                'name' => 'Student User',
                'email' => 'student@example.com',
            ],
            [
                'name' => 'Advanced User',
                'email' => 'advanced@example.com',
            ],
        ])->each(fn (array $attributes) => User::factory()->create($attributes));

        $this->call([
            CourseContentSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
