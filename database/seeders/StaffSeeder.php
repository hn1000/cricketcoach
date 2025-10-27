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

        // Cricket coaching staff data
        $staffData = [
            [
                'first_name' => 'Rajesh',
                'last_name' => 'Sharma',
                'email' => 'rajesh.sharma@example.com',
                'phone' => '+44 7700 900123',
                'position' => 'Head Cricket Coach',
                'department' => 'Batting Specialist',
                'hire_date' => '2022-01-15',
                'is_active' => true,
            ],
            [
                'first_name' => 'Andrew',
                'last_name' => 'Mitchell',
                'email' => 'andrew.mitchell@example.com',
                'phone' => '+44 7700 900124',
                'position' => 'Fast Bowling Coach',
                'department' => 'Pace Bowling',
                'hire_date' => '2022-03-20',
                'is_active' => true,
            ],
            [
                'first_name' => 'Priya',
                'last_name' => 'Patel',
                'email' => 'priya.patel@example.com',
                'phone' => '+44 7700 900125',
                'position' => 'Spin Bowling Coach',
                'department' => 'Spin Bowling',
                'hire_date' => '2022-05-10',
                'is_active' => true,
            ],
            [
                'first_name' => 'Marcus',
                'last_name' => 'Johnson',
                'email' => 'marcus.johnson@example.com',
                'phone' => '+44 7700 900126',
                'position' => 'All-Rounder Coach',
                'department' => 'All-Round Skills',
                'hire_date' => '2022-06-01',
                'is_active' => true,
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Thompson',
                'email' => 'sarah.thompson@example.com',
                'phone' => '+44 7700 900127',
                'position' => 'Wicket-Keeping Coach',
                'department' => 'Wicket-Keeping',
                'hire_date' => '2022-07-15',
                'is_active' => true,
            ],
            [
                'first_name' => 'Mohammed',
                'last_name' => 'Ali',
                'email' => 'mohammed.ali@example.com',
                'phone' => '+44 7700 900128',
                'position' => 'Fielding Coach',
                'department' => 'Fielding & Fitness',
                'hire_date' => '2022-08-01',
                'is_active' => true,
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Roberts',
                'email' => 'emily.roberts@example.com',
                'phone' => '+44 7700 900129',
                'position' => 'Youth Cricket Coach',
                'department' => 'Youth Development',
                'hire_date' => '2022-09-20',
                'is_active' => true,
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Williams',
                'email' => 'david.williams@example.com',
                'phone' => '+44 7700 900130',
                'position' => 'Opening Batsman Coach',
                'department' => 'Batting Specialist',
                'hire_date' => '2022-10-10',
                'is_active' => true,
            ],
            [
                'first_name' => 'Anjali',
                'last_name' => 'Kumar',
                'email' => 'anjali.kumar@example.com',
                'phone' => '+44 7700 900131',
                'position' => 'Middle Order Coach',
                'department' => 'Batting Specialist',
                'hire_date' => '2022-11-05',
                'is_active' => true,
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Taylor',
                'email' => 'james.taylor@example.com',
                'phone' => '+44 7700 900132',
                'position' => 'Power Hitting Coach',
                'department' => 'Batting Specialist',
                'hire_date' => '2023-01-10',
                'is_active' => true,
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Brown',
                'email' => 'sophie.brown@example.com',
                'phone' => '+44 7700 900133',
                'position' => 'Technical Coach',
                'department' => 'Technical Analysis',
                'hire_date' => '2023-02-15',
                'is_active' => true,
            ],
            [
                'first_name' => 'Ryan',
                'last_name' => 'O\'Connor',
                'email' => 'ryan.oconnor@example.com',
                'phone' => '+44 7700 900134',
                'position' => 'Strength & Conditioning',
                'department' => 'Fitness',
                'hire_date' => '2023-03-01',
                'is_active' => true,
            ],
            [
                'first_name' => 'Amit',
                'last_name' => 'Singh',
                'email' => 'amit.singh@example.com',
                'phone' => '+44 7700 900135',
                'position' => 'Junior Cricket Coach',
                'department' => 'Youth Development',
                'hire_date' => '2023-04-20',
                'is_active' => true,
            ],
            [
                'first_name' => 'Charlotte',
                'last_name' => 'Davies',
                'email' => 'charlotte.davies@example.com',
                'phone' => '+44 7700 900136',
                'position' => 'Assistant Coach',
                'department' => 'All-Round Skills',
                'hire_date' => '2023-05-10',
                'is_active' => true,
            ],
            [
                'first_name' => 'Nathan',
                'last_name' => 'Clarke',
                'email' => 'nathan.clarke@example.com',
                'phone' => '+44 7700 900137',
                'position' => 'Bowling Specialist',
                'department' => 'Pace Bowling',
                'hire_date' => '2023-06-01',
                'is_active' => true,
            ],
            [
                'first_name' => 'Deepak',
                'last_name' => 'Verma',
                'email' => 'deepak.verma@example.com',
                'phone' => '+44 7700 900138',
                'position' => 'T20 Specialist Coach',
                'department' => 'All-Round Skills',
                'hire_date' => '2023-07-15',
                'is_active' => true,
            ],
            [
                'first_name' => 'Lucy',
                'last_name' => 'Anderson',
                'email' => 'lucy.anderson@example.com',
                'phone' => '+44 7700 900139',
                'position' => 'Women\'s Cricket Coach',
                'department' => 'Youth Development',
                'hire_date' => '2023-08-20',
                'is_active' => true,
            ],
            [
                'first_name' => 'Ben',
                'last_name' => 'Hughes',
                'email' => 'ben.hughes@example.com',
                'phone' => '+44 7700 900140',
                'position' => 'Leg Spin Coach',
                'department' => 'Spin Bowling',
                'hire_date' => '2023-09-10',
                'is_active' => true,
            ],
        ];

        // Assign staff to companies (distribute evenly)
        foreach ($companies as $index => $company) {
            // Assign 2-3 staff members to each active company
            if ($company->is_active) {
                $staffCount = rand(2, 3);

                for ($i = 0; $i < $staffCount && !empty($staffData); $i++) {
                    $staff = array_shift($staffData);
                    Staff::create(array_merge($staff, ['company_id' => $company->id]));
                }
            }
        }

        $this->command->info('Staff seeded successfully.');
    }
}
