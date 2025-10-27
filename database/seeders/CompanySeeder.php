<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Elite Cricket Academy',
                'description' => 'Premier cricket coaching academy specializing in professional batting and bowling techniques. We offer personalized coaching sessions for players of all skill levels, from beginners to advanced cricketers preparing for competitive matches.',
                'logo' => 'https://via.placeholder.com/150/2E7D32/FFFFFF?text=Elite+Cricket',
                'website' => 'https://elitecricket.example.com',
                'email' => 'coaching@elitecricket.example.com',
                'phone' => '+44 20 7123 4567',
                'address' => 'Lord\'s Cricket Ground, St John\'s Wood, London NW8 8QN',
                'is_active' => true,
            ],
            [
                'name' => 'Pace & Spin Cricket School',
                'description' => 'Specialized cricket coaching focusing on fast bowling and spin techniques. Our experienced coaches have played at international levels and provide expert guidance in all aspects of cricket.',
                'logo' => 'https://via.placeholder.com/150/1565C0/FFFFFF?text=Pace+%26+Spin',
                'website' => 'https://paceandspin.example.com',
                'email' => 'info@paceandspin.example.com',
                'phone' => '+44 161 789 4567',
                'address' => 'Emirates Old Trafford, Talbot Road, Manchester M16 0PX',
                'is_active' => true,
            ],
            [
                'name' => 'Junior Cricket Champions',
                'description' => 'Youth cricket development program designed for children aged 6-16. We focus on building fundamental skills, teamwork, and sportsmanship while making cricket fun and engaging for young players.',
                'logo' => 'https://via.placeholder.com/150/F57C00/FFFFFF?text=Junior+Cricket',
                'website' => 'https://juniorcricket.example.com',
                'email' => 'juniors@cricketcamp.example.com',
                'phone' => '+44 121 456 7890',
                'address' => 'Edgbaston Cricket Ground, Birmingham B5 7QU',
                'is_active' => true,
            ],
            [
                'name' => 'All-Rounder Cricket Institute',
                'description' => 'Comprehensive cricket training institute offering coaching in batting, bowling, wicket-keeping, and fielding. We provide both individual sessions and group training programs with state-of-the-art facilities.',
                'logo' => 'https://via.placeholder.com/150/C62828/FFFFFF?text=All-Rounder',
                'website' => 'https://allrounder-cricket.example.com',
                'email' => 'training@allrounder-cricket.example.com',
                'phone' => '+44 113 234 5678',
                'address' => 'Headingley Cricket Ground, Leeds LS6 3BU',
                'is_active' => true,
            ],
            [
                'name' => 'Metropolitan Cricket Coaching',
                'description' => 'Professional cricket coaching services in the heart of the city. We offer flexible scheduling, indoor nets, video analysis, and personalized training plans for serious cricketers.',
                'logo' => 'https://via.placeholder.com/150/6A1B9A/FFFFFF?text=Metro+Cricket',
                'website' => 'https://metrocricket.example.com',
                'email' => 'bookings@metrocricket.example.com',
                'phone' => '+44 20 8765 4321',
                'address' => 'The Oval, Kennington, London SE11 5SS',
                'is_active' => true,
            ],
            [
                'name' => 'County Cricket Development',
                'description' => 'County-level cricket coaching program affiliated with regional cricket boards. We prepare players for county cricket trials and offer advanced coaching for competitive cricketers.',
                'logo' => 'https://via.placeholder.com/150/00796B/FFFFFF?text=County+Cricket',
                'website' => 'https://countycricket.example.com',
                'email' => 'development@countycricket.example.com',
                'phone' => '+44 117 987 6543',
                'address' => 'County Ground, Bristol BS7 9EJ',
                'is_active' => true,
            ],
            [
                'name' => 'Summer Cricket Camp',
                'description' => 'Seasonal cricket camp offering intensive training during school holidays. Perfect for young cricketers looking to improve their game during the summer break.',
                'logo' => 'https://via.placeholder.com/150/FBC02D/FFFFFF?text=Summer+Camp',
                'website' => 'https://summercricketcamp.example.com',
                'email' => 'camp@summercricket.example.com',
                'phone' => '+44 114 567 8901',
                'address' => 'Bramall Lane, Sheffield S2 4SU',
                'is_active' => false,
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
