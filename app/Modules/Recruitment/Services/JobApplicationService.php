<?php

namespace App\Modules\Recruitment\Services;

use App\Core\BaseService;
use App\Modules\Recruitment\Interfaces\JobApplicationRepositoryInterface;
use App\Modules\Recruitment\Models\JobApplication;
use App\Modules\Recruitment\Models\Interview;
use App\Modules\Recruitment\DTOs\UpdateJobApplicationStatusData;
use App\Modules\Recruitment\DTOs\ScheduleInterviewData;
use App\Modules\Recruitment\DTOs\UpdateInterviewData;
use App\Modules\Recruitment\DTOs\PublicJobApplicationData;
use Illuminate\Support\Carbon;

/**
 * @property JobApplicationRepositoryInterface $repository
 */
class JobApplicationService extends BaseService
{
    public function __construct(JobApplicationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getByPosting(int $postingId)
    {
        return $this->repository->getByPosting($postingId);
    }

    public function getPaginatedList(array $filters = [], int $perPage = 20)
    {
        return $this->repository->getPaginatedList($filters, $perPage);
    }

    public function storePublicApplication(PublicJobApplicationData $data, int $jobPostingId, ?string $resumePath)
    {
        $applicationData = [
            'job_posting_id' => $jobPostingId,
            'first_name'     => $data->first_name,
            'last_name'      => $data->last_name,
            'email'          => $data->email,
            'phone'          => $data->phone,
            'cover_letter'   => $data->cover_letter,
            'resume_path'    => $resumePath,
            'status'         => 'new',
            'applied_at'     => Carbon::now(),
            'tenant_id'      => saas_tenant('id'),
        ];

        return $this->repository->create($applicationData);
    }

    public function updateStatus(JobApplication $application, UpdateJobApplicationStatusData $data)
    {
        $application->update([
            'status' => $data->status,
        ]);
        // Note: notes could be saved if there was a field or relationship, currently ignored based on existing controller code.
        return $application;
    }

    public function scheduleInterview(JobApplication $application, ScheduleInterviewData $data)
    {
        Interview::create(array_merge($data->toArray(), [
            'job_application_id' => $application->id,
            'status'             => 'scheduled',
        ]));

        if ($application->status === 'shortlisted' || $application->status === 'reviewing') {
            $application->update(['status' => 'interview']);
        }

        return $application;
    }

    public function updateInterview(Interview $interview, UpdateInterviewData $data)
    {
        $interview->update([
            'status' => $data->status,
            'feedback' => $data->feedback,
        ]);
        return $interview;
    }

    public function hire(JobApplication $application)
    {
        $application->update(['status' => 'hired']);
        return $application;
    }
}
