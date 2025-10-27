<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all companies
        $companies = Company::all();

        if ($companies->isEmpty()) {
            $this->command->warn('No companies found. Please run CompanySeeder first.');
            return;
        }

        // Sample staff data
        $staffData = [
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '555-0101',
                'position' => 'Head Coach',
                'department' => 'Coaching',
                'hire_date' => '2023-01-15',
                'is_active' => true,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '555-0102',
                'position' => 'Assistant Coach',
                'department' => 'Coaching',
                'hire_date' => '2023-03-20',
                'is_active' => true,
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Williams',
                'email' => 'michael.williams@example.com',
                'phone' => '555-0103',
                'position' => 'Batting Coach',
                'department' => 'Coaching',
                'hire_date' => '2023-05-10',
                'is_active' => true,
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'email' => 'emily.brown@example.com',
                'phone' => '555-0104',
                'position' => 'Bowling Coach',
                'department' => 'Coaching',
                'hire_date' => '2023-06-01',
                'is_active' => true,
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Taylor',
                'email' => 'david.taylor@example.com',
                'phone' => '555-0105',
                'position' => 'Fitness Trainer',
                'department' => 'Training',
                'hire_date' => '2023-07-15',
                'is_active' => true,
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'email' => 'lisa.anderson@example.com',
                'phone' => '555-0106',
                'position' => 'Operations Manager',
                'department' => 'Administration',
                'hire_date' => '2023-02-01',
                'is_active' => true,
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'email' => 'james.wilson@example.com',
                'phone' => '555-0107',
                'position' => 'Youth Coach',
                'department' => 'Youth Development',
                'hire_date' => '2023-08-20',
                'is_active' => false,
            ],
        ];

        // Assign staff to companies (distribute evenly)
        foreach ($companies as $index => $company) {
            // Assign 2-3 staff members to each company
            $staffCount = rand(2, 3);

            for ($i = 0; $i < $staffCount && !empty($staffData); $i++) {
                $staff = array_shift($staffData);
                Staff::create(array_merge($staff, ['company_id' => $company->id]));
            }
        }

        $this->command->info('Staff seeded successfully.');
    }
}
