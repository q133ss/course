<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = collect([
            ['name' => 'Администратор', 'slug' => Role::ADMIN],
            ['name' => 'Пользователь', 'slug' => Role::USER],
        ])->mapWithKeys(function (array $role) {
            $model = Role::query()->firstOrCreate([
                'slug' => $role['slug'],
            ], $role);

            return [$model->slug => $model];
        });

        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'role_id' => $roles[Role::ADMIN]->id,
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

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
        ])->each(function (array $attributes) use ($roles) {
            User::factory()->create($attributes + [
                'role_id' => $roles[Role::USER]->id,
            ]);
        });

        $this->call([
            CourseContentSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
