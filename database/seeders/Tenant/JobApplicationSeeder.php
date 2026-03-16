<?php

namespace Database\Seeders\Tenant;

use App\Models\Tenant;
use App\Modules\Recruitment\Models\JobPosting;
use App\Modules\Recruitment\Models\JobApplication;
use App\Modules\Recruitment\Models\Interview;
use App\Modules\HR\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class JobApplicationSeeder extends Seeder
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

        $postings = JobPosting::where('tenant_id', $tenantId)->where('status', 'published')->get();
        if ($postings->isEmpty()) {
            return;
        }

        $employees = Employee::from('shared.employees')->where('tenant_id', $tenantId)->where('status', 'active')->get();

        $candidatesData = [
            ['first_name' => 'Amit', 'last_name' => 'Sharma', 'email' => 'amit.s@example.com', 'phone' => '+91 9876543210', 'status' => 'new'],
            ['first_name' => 'Priya', 'last_name' => 'Singh', 'email' => 'priya.singh@example.com', 'phone' => '+91 9123456780', 'status' => 'reviewing'],
            ['first_name' => 'Rahul', 'last_name' => 'Verma', 'email' => 'rahul.v@example.com', 'status' => 'shortlisted'],
            ['first_name' => 'Sneha', 'last_name' => 'Kapoor', 'email' => 'sneha.k@example.com', 'phone' => '+91 9988776655', 'status' => 'interview'],
            ['first_name' => 'Vikram', 'last_name' => 'Yadav', 'email' => 'vikram.yadav@test.com', 'status' => 'offered'],
            ['first_name' => 'Aisha', 'last_name' => 'Khan', 'email' => 'aisha.k@test.com', 'phone' => '+91 9898989898', 'status' => 'hired'],
            ['first_name' => 'Kunal', 'last_name' => 'Desai', 'email' => 'kunal.d@example.com', 'status' => 'rejected'],
            ['first_name' => 'Neha', 'last_name' => 'Gupta', 'email' => 'neha.g@example.com', 'phone' => '+91 9112233445', 'status' => 'new'],
        ];

        foreach ($candidatesData as $idx => $candidate) {
            $posting = $postings->random();

            $application = new JobApplication([
                'job_posting_id' => $posting->id,
                'first_name' => $candidate['first_name'],
                'last_name' => $candidate['last_name'],
                'email' => $candidate['email'],
                'phone' => $candidate['phone'] ?? null,
                'cover_letter' => "Hi Hiring Manager,\n\nI am writing to express my interest in the " . $posting->title . " position. I believe my skills and experience make me a strong candidate for this role.",
                'status' => $candidate['status'],
                'applied_at' => Carbon::now()->subDays(rand(1, 14))->subHours(rand(1, 24)),
            ]);
            $application->tenant_id = $tenantId;
            $application->save();

            // Add interviews for those in interview, offered, hired
            if (in_array($candidate['status'], ['interview', 'offered', 'hired'])) {
                $interviewer = $employees->isNotEmpty() ? $employees->random() : null;
                
                // Past completed interview
                $interview1 = new Interview([
                    'job_application_id' => $application->id,
                    'interviewer_id' => $interviewer ? $interviewer->id : null,
                    'type' => 'phone',
                    'interview_date' => Carbon::parse($application->applied_at)->addDays(2),
                    'location' => 'Phone',
                    'status' => 'completed',
                    'feedback' => 'Good communication skills, clear on basics. Recommended for technical round.',
                ]);
                $interview1->tenant_id = $tenantId;
                $interview1->save();

                // Future or recent technical interview
                if ($candidate['status'] === 'interview') {
                    $interview2 = new Interview([
                        'job_application_id' => $application->id,
                        'interviewer_id' => $interviewer ? $interviewer->id : null,
                        'type' => 'technical',
                        'interview_date' => Carbon::now()->addDays(rand(1, 3))->setHour(14)->setMinute(0),
                        'location' => 'Google Meet',
                        'status' => 'scheduled',
                    ]);
                    $interview2->tenant_id = $tenantId;
                    $interview2->save();
                } else {
                    $interview3 = new Interview([
                        'job_application_id' => $application->id,
                        'interviewer_id' => $interviewer ? $interviewer->id : null,
                        'type' => 'technical',
                        'interview_date' => Carbon::parse($application->applied_at)->addDays(5),
                        'location' => 'Google Meet',
                        'status' => 'completed',
                        'feedback' => 'Strong technical knowledge. Solved all problems efficiently.',
                    ]);
                    $interview3->tenant_id = $tenantId;
                    $interview3->save();
                }
            }
        }

        if (isset($this->command)) {
            $this->command->info('Job Applications seeded successfully for tenant: ' . $tenantId);
        } else {
            echo "Job Applications seeded successfully for tenant: " . $tenantId . "\n";
        }
    }
}
