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

        User::query()->updateOrCreate(
            ['email' => 'admin@email.net'],
            [
                'role_id' => $roles[Role::ADMIN]->id,
                'name' => 'Администратор',
                'password' => Hash::make('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'user@email.net'],
            [
                'role_id' => $roles[Role::USER]->id,
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            CourseContentSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
