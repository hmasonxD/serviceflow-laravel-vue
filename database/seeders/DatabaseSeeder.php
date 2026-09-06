<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'ServiceFlow Admin',
            'email' => 'admin@serviceflow.test',
            'role' => UserRole::Admin,
        ]);

        User::factory()->create([
            'name' => 'ServiceFlow Technician',
            'email' => 'technician@serviceflow.test',
            'role' => UserRole::Technician,
        ]);

        Customer::factory()
            ->count(20)
            ->create([
                'created_by' => $admin->id,
            ]);
    }
}
