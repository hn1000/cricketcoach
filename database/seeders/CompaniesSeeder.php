<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Lord\'s Cricket Academy',
                'description' => 'Premier cricket coaching facility in London. Expert coaches with international experience offering comprehensive training programs for all ages and skill levels.',
                'email' => 'info@lordscricket.com',
                'phone' => '020 7289 1611',
                'address' => 'St John\'s Wood Road, London',
                'postcode' => 'NW8 8QN',
                'latitude' => 51.5294,
                'longitude' => -0.1727,
                'is_active' => true,
                'staff' => [
                    [
                        'first_name' => 'James',
                        'last_name' => 'Anderson',
                        'email' => 'j.anderson@lordscricket.com',
                        'phone' => '020 7289 1612',
                        'position' => 'Head Coach',
                        'department' => 'Coaching',
                        'bio' => 'Former professional cricketer with 15 years of coaching experience. Specialized in fast bowling techniques and junior development.',
                        'specialties' => ['bowling', 'junior', 'all-rounder'],
                        'lesson_types' => ['in-person', 'group', 'individual'],
                        'facilities' => ['indoor-studio', 'outdoor-nets', 'video-analysis'],
                        'technology_available' => ['video-analysis', 'speed-gun', 'bowling-machine'],
                        'is_active' => true,
                    ],
                    [
                        'first_name' => 'Sarah',
                        'last_name' => 'Taylor',
                        'email' => 's.taylor@lordscricket.com',
                        'phone' => '020 7289 1613',
                        'position' => 'Wicket-keeping Coach',
                        'department' => 'Coaching',
                        'bio' => 'International wicket-keeper with extensive experience coaching at all levels. Passionate about developing young talent.',
                        'specialties' => ['wicket-keeping', 'batting', 'junior'],
                        'lesson_types' => ['in-person', 'individual', 'academy'],
                        'facilities' => ['indoor-studio', 'outdoor-nets'],
                        'technology_available' => ['video-analysis'],
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Birmingham Cricket Centre',
                'description' => 'State-of-the-art cricket training center in the heart of Birmingham. Offering indoor and outdoor facilities with cutting-edge technology.',
                'email' => 'contact@birminghamcricket.co.uk',
                'phone' => '0121 446 4000',
                'address' => 'Edgbaston Stadium, Birmingham',
                'postcode' => 'B5 7QU',
                'latitude' => 52.4558,
                'longitude' => -1.9025,
                'is_active' => true,
                'staff' => [
                    [
                        'first_name' => 'Michael',
                        'last_name' => 'Vaughan',
                        'email' => 'm.vaughan@birminghamcricket.co.uk',
                        'phone' => '0121 446 4001',
                        'position' => 'Director of Coaching',
                        'department' => 'Coaching',
                        'bio' => 'Former England captain with a passion for developing batting technique and mental strength in cricketers.',
                        'specialties' => ['batting', 'all-rounder'],
                        'lesson_types' => ['in-person', 'virtual', 'individual', 'group'],
                        'facilities' => ['indoor-studio', 'outdoor-nets', 'grass-pitch', 'video-analysis'],
                        'technology_available' => ['video-analysis', 'ball-tracking', 'biomechanics'],
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Manchester Cricket Academy',
                'description' => 'Professional cricket coaching in Manchester. Specializing in youth development and performance coaching with modern training methods.',
                'email' => 'academy@manchestercricket.com',
                'phone' => '0161 282 4000',
                'address' => 'Old Trafford, Manchester',
                'postcode' => 'M16 0PX',
                'latitude' => 53.4564,
                'longitude' => -2.2881,
                'is_active' => true,
                'staff' => [
                    [
                        'first_name' => 'Andrew',
                        'last_name' => 'Flintoff',
                        'email' => 'a.flintoff@manchestercricket.com',
                        'phone' => '0161 282 4001',
                        'position' => 'Senior Coach',
                        'department' => 'Coaching',
                        'bio' => 'All-rounder specialist focusing on power hitting and seam bowling. Experience coaching at international level.',
                        'specialties' => ['all-rounder', 'batting', 'bowling'],
                        'lesson_types' => ['in-person', 'group', 'academy'],
                        'facilities' => ['indoor-studio', 'outdoor-nets', 'astro-pitch'],
                        'technology_available' => ['video-analysis', 'bowling-machine', 'speed-gun'],
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Leeds Cricket Performance Centre',
                'description' => 'Elite cricket training facility offering personalized coaching programs. Focus on technique refinement and match preparation.',
                'email' => 'info@leedscricket.co.uk',
                'phone' => '0113 203 3500',
                'address' => 'Headingley Stadium, Leeds',
                'postcode' => 'LS6 3BU',
                'latitude' => 53.8175,
                'longitude' => -1.5822,
                'is_active' => true,
                'staff' => [
                    [
                        'first_name' => 'Joe',
                        'last_name' => 'Root',
                        'email' => 'j.root@leedscricket.co.uk',
                        'phone' => '0113 203 3501',
                        'position' => 'Batting Coach',
                        'department' => 'Coaching',
                        'bio' => 'Batting specialist with focus on classical technique and modern T20 skills. Experienced in coaching junior and senior players.',
                        'specialties' => ['batting', 'junior'],
                        'lesson_types' => ['in-person', 'individual', 'virtual'],
                        'facilities' => ['indoor-studio', 'video-analysis'],
                        'technology_available' => ['video-analysis', 'ball-tracking'],
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Bristol Junior Cricket School',
                'description' => 'Dedicated to developing young cricketers in a fun and supportive environment. Age-specific programs from 5-18 years.',
                'email' => 'juniors@bristolcricket.com',
                'phone' => '0117 910 8000',
                'address' => 'County Ground, Bristol',
                'postcode' => 'BS7 9EJ',
                'latitude' => 51.4839,
                'longitude' => -2.5831,
                'is_active' => true,
                'staff' => [
                    [
                        'first_name' => 'Charlotte',
                        'last_name' => 'Edwards',
                        'email' => 'c.edwards@bristolcricket.com',
                        'phone' => '0117 910 8001',
                        'position' => 'Junior Development Coach',
                        'department' => 'Coaching',
                        'bio' => 'Specialist in junior cricket development with emphasis on building fundamentals and fostering love for the game.',
                        'specialties' => ['junior', 'batting', 'fielding'],
                        'lesson_types' => ['in-person', 'group', 'academy'],
                        'facilities' => ['outdoor-nets', 'grass-pitch', 'astro-pitch'],
                        'technology_available' => ['bowling-machine'],
                        'is_active' => true,
                    ],
                ],
            ],
        ];

        foreach ($companies as $companyData) {
            $staffData = $companyData['staff'];
            unset($companyData['staff']);

            $company = Company::create($companyData);

            foreach ($staffData as $staff) {
                $company->staff()->create($staff);
            }
        }
    }
}
