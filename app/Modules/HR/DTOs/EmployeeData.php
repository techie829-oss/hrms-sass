<?php

namespace App\Modules\HR\DTOs;

class EmployeeData
{
    public function __construct(
        public readonly string $employee_id,
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly \DateTimeImmutable $date_of_joining,
        public readonly string $employment_type,
        public readonly string $status,
        public readonly float $basic_salary,

        // Optional Fields
        public readonly ?string $personal_email = null,
        public readonly ?string $country_code = null,
        public readonly ?string $phone = null,
        public readonly ?string $gender = null,
        public readonly ?\DateTimeImmutable $date_of_birth = null,
        public readonly ?string $pan_number = null,
        public readonly ?string $aadhar_number = null,
        public readonly ?string $passport_number = null,
        public readonly ?string $current_address = null,
        public readonly ?string $permanent_address = null,
        public readonly ?string $emergency_contact_name = null,
        public readonly ?string $emergency_contact_phone = null,
        public readonly ?string $emergency_contact_relation = null,
        public readonly ?int $reporting_to = null,
        public readonly ?int $department_id = null,
        public readonly ?int $designation_id = null,

        // Login & System Fields (Handled in controller, but passed if needed)
        public readonly ?string $checkin_required = null,
    ) {}

    /**
     * Create DTO from validated array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            employee_id: $data['employee_id'],
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            email: $data['email'],
            date_of_joining: new \DateTimeImmutable($data['date_of_joining']),
            employment_type: $data['employment_type'],
            status: $data['status'],
            basic_salary: (float) $data['basic_salary'],

            personal_email: $data['personal_email'] ?? null,
            country_code: $data['country_code'] ?? null,
            phone: $data['phone'] ?? null,
            gender: $data['gender'] ?? null,
            date_of_birth: isset($data['date_of_birth']) ? new \DateTimeImmutable($data['date_of_birth']) : null,
            pan_number: $data['pan_number'] ?? null,
            aadhar_number: $data['aadhar_number'] ?? null,
            passport_number: $data['passport_number'] ?? null,
            current_address: $data['current_address'] ?? null,
            permanent_address: $data['permanent_address'] ?? null,
            emergency_contact_name: $data['emergency_contact_name'] ?? null,
            emergency_contact_phone: $data['emergency_contact_phone'] ?? null,
            emergency_contact_relation: $data['emergency_contact_relation'] ?? null,
            reporting_to: isset($data['reporting_to']) ? (int) $data['reporting_to'] : null,
            department_id: isset($data['department_id']) ? (int) $data['department_id'] : null,
            designation_id: isset($data['designation_id']) ? (int) $data['designation_id'] : null,

            checkin_required: $data['checkin_required'] ?? null,
        );
    }

    /**
     * Convert DTO back to array for Eloquent.
     */
    public function toArray(): array
    {
        return [
            'employee_id' => $this->employee_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'date_of_joining' => $this->date_of_joining->format('Y-m-d'),
            'employment_type' => $this->employment_type,
            'status' => $this->status,
            'basic_salary' => $this->basic_salary,
            'personal_email' => $this->personal_email,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'pan_number' => $this->pan_number,
            'aadhar_number' => $this->aadhar_number,
            'passport_number' => $this->passport_number,
            'current_address' => $this->current_address,
            'permanent_address' => $this->permanent_address,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'emergency_contact_relation' => $this->emergency_contact_relation,
            'reporting_to' => $this->reporting_to,
            'department_id' => $this->department_id,
            'designation_id' => $this->designation_id,
        ];
    }
}
