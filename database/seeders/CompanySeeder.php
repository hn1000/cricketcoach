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
                'name' => 'Acme Corporation',
                'description' => 'A leading provider of innovative solutions for modern businesses.',
                'logo' => 'https://via.placeholder.com/150/0000FF/FFFFFF?text=ACME',
                'website' => 'https://acme-corp.example.com',
                'email' => 'info@acme-corp.example.com',
                'phone' => '+1 (555) 123-4567',
                'address' => '123 Business St, Suite 100, New York, NY 10001',
                'is_active' => true,
            ],
            [
                'name' => 'TechStart Inc',
                'description' => 'Cutting-edge technology solutions for the digital age.',
                'logo' => 'https://via.placeholder.com/150/FF0000/FFFFFF?text=TechStart',
                'website' => 'https://techstart.example.com',
                'email' => 'contact@techstart.example.com',
                'phone' => '+1 (555) 234-5678',
                'address' => '456 Innovation Ave, San Francisco, CA 94102',
                'is_active' => true,
            ],
            [
                'name' => 'Global Services Ltd',
                'description' => 'Providing world-class services across multiple industries.',
                'logo' => 'https://via.placeholder.com/150/00FF00/FFFFFF?text=Global',
                'website' => 'https://globalservices.example.com',
                'email' => 'hello@globalservices.example.com',
                'phone' => '+1 (555) 345-6789',
                'address' => '789 Corporate Blvd, Chicago, IL 60601',
                'is_active' => true,
            ],
            [
                'name' => 'Innovation Labs',
                'description' => 'Research and development company focused on breakthrough technologies.',
                'logo' => 'https://via.placeholder.com/150/FFA500/FFFFFF?text=Innovation',
                'website' => 'https://innovationlabs.example.com',
                'email' => 'labs@innovationlabs.example.com',
                'phone' => '+1 (555) 456-7890',
                'address' => '321 Science Park, Boston, MA 02101',
                'is_active' => true,
            ],
            [
                'name' => 'Digital Solutions Group',
                'description' => 'Comprehensive digital transformation services for enterprises.',
                'logo' => 'https://via.placeholder.com/150/800080/FFFFFF?text=Digital',
                'website' => 'https://digitalsolutions.example.com',
                'email' => 'info@digitalsolutions.example.com',
                'phone' => '+1 (555) 567-8901',
                'address' => '654 Tech Center Dr, Austin, TX 78701',
                'is_active' => false,
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
