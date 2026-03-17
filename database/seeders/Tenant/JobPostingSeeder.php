<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant;
use App\Modules\Recruitment\Models\JobPosting;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenantId = tenant('id') ?? 'rkservices';
        
        if (!$tenantId) {
            echo "Error: This seeder must be run within a tenant context.\n";
            return;
        }

        $jobs = [
            [
                'title' => 'Senior Frontend Developer',
                'description' => 'We are looking for an experienced frontend developer to lead our UI team. You will be working with React, Tailwind CSS, and Alpine.js to build premium web applications.',
                'requirements' => "• 5+ years of experience with React/Vue\n• Expert in HTML/CSS and responsive design\n• Experience with state management\n• Strong understanding of web performance",
                'location' => 'Remote (India)',
                'employment_type' => 'full_time',
                'salary_range' => '₹18,00,000 - ₹25,00,000',
                'status' => 'open',
                'closing_date' => now()->addDays(30),
            ],
            [
                'title' => 'Backend Laravel Engineer',
                'description' => 'Join our core engineering team to build scalable and robust RESTful APIs in Laravel. This role involves maintaining a multi-tenant SaaS architecture.',
                'requirements' => "• 3+ years of experience with Laravel & PHP 8\n• Strong SQL skills (MySQL/PostgreSQL)\n• Experience with multi-tenancy is a big plus\n• Solid understanding of RESTful API design",
                'location' => 'Bangalore, India (Hybrid)',
                'employment_type' => 'full_time',
                'salary_range' => '₹15,00,000 - ₹22,00,000',
                'status' => 'open',
                'closing_date' => now()->addDays(15),
            ],
            [
                'title' => 'Product Designer (UI/UX)',
                'description' => 'We are looking for a creative UI/UX designer to craft modern, premium SaaS interfaces. If you love clean designs and DaisyUI, this is for you.',
                'requirements' => "• 3+ years of product design experience\n• Expert in Figma\n• Portfolio showcasing SaaS products\n• Understanding of front-end capabilities",
                'location' => 'Mumbai, India',
                'employment_type' => 'full_time',
                'salary_range' => '₹10,00,000 - ₹18,00,000',
                'status' => 'draft',
                'closing_date' => now()->addDays(45),
            ],
            [
                'title' => 'Marketing Intern',
                'description' => 'Kickstart your career in SaaS product marketing. You will help with content creation, social media management, and email campaigns.',
                'requirements' => "• Excellent written communication skills\n• Basic understanding of SEO\n• Creative mindset\n• Available for 6 months",
                'location' => 'Remote',
                'employment_type' => 'internship',
                'salary_range' => '₹25,000/month',
                'status' => 'open',
                'closing_date' => now()->addDays(7),
            ],
        ];

        foreach ($jobs as $job) {
            $posting = new JobPosting($job);
            $posting->tenant_id = $tenantId;
            $posting->save();
        }

        if (isset($this->command)) {
            $this->command->info('Dummy Job Postings seeded successfully for tenant: ' . $tenantId);
        } else {
            echo "Dummy Job Postings seeded successfully for tenant: " . $tenantId . "\n";
        }
    }
}
