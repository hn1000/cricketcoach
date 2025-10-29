<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanyPortalUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users for company portal access

        // 1. Company Owner - can access Lord's Cricket Academy
        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'John Owner',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 2. Company Manager - can access MCC Cricket School
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Sarah Manager',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 3. Multi-company User - can access both Lord's and The Oval
        $multiUser = User::firstOrCreate(
            ['email' => 'multi@example.com'],
            [
                'name' => 'David Multi',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        // 4. Admin user (for testing admin panel)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Attach users to companies with roles
        // Find first 3 companies (created by CompaniesSeeder)
        $companies = Company::limit(3)->get();

        if ($companies->count() >= 3) {
            // Attach owner to first company (Lord's Cricket Academy)
            if (!$owner->companies()->where('company_id', $companies[0]->id)->exists()) {
                $owner->companies()->attach($companies[0]->id, ['role' => 'owner']);
            }

            // Attach manager to second company (MCC Cricket School)
            if (!$manager->companies()->where('company_id', $companies[1]->id)->exists()) {
                $manager->companies()->attach($companies[1]->id, ['role' => 'manager']);
            }

            // Attach multi-user to first and third companies (Lord's and The Oval)
            if (!$multiUser->companies()->where('company_id', $companies[0]->id)->exists()) {
                $multiUser->companies()->attach($companies[0]->id, ['role' => 'manager']);
            }
            if (!$multiUser->companies()->where('company_id', $companies[2]->id)->exists()) {
                $multiUser->companies()->attach($companies[2]->id, ['role' => 'owner']);
            }
        }

        $this->command->info('Company portal test users created successfully!');
        $this->command->info('');
        $this->command->info('Test Credentials:');
        $this->command->info('1. Owner (Lord\'s Cricket Academy): owner@example.com / password');
        $this->command->info('2. Manager (MCC Cricket School): manager@example.com / password');
        $this->command->info('3. Multi-Company (Lord\'s & The Oval): multi@example.com / password');
        $this->command->info('4. Admin (all companies): admin@example.com / password');
        $this->command->info('');
        $this->command->info('Access company portal at: /company/dashboard');
    }
}
