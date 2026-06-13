# Tenant Panel Pages & Routes Tracking

This file tracks the migration, styling, and verification status of all tenant panel pages in the HRMS system.

## Status Legend
- `[ ]` Pending
- `[x]` Completed / Verified

---

## 1. Core Pages (Dashboard & Profile)

- [x] **Tenant Dashboard**
  - **Route Name**: `tenant.dashboard`
  - **URL**: `/dashboard`
  - **Blade Template**: `resources/views/tenant/dashboard.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Edit Profile**
  - **Route Name**: `profile.edit`
  - **URL**: `/profile`
  - **Blade Template**: `resources/views/profile/edit.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
  - **Partials**:
    - [x] Delete User: `resources/views/profile/partials/delete-user-form.blade.php`
    - [x] Update Password: `resources/views/profile/partials/update-password-form.blade.php`
    - [x] Update Profile Info: `resources/views/profile/partials/update-profile-information-form.blade.php`

---

## 2. Workforce (HR Module)

- [x] **Employee Directory (List)**
  - **Route Name**: `hr.employees.index`
  - **URL**: `/hr/employees`
  - **Blade Template**: `app/Modules/HR/resources/views/employees/index.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Create Employee**
  - **Route Name**: `hr.employees.create`
  - **URL**: `/hr/employees/create`
  - **Blade Template**: `app/Modules/HR/resources/views/employees/create.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **View Employee Profile**
  - **Route Name**: `hr.employees.show`
  - **URL**: `/hr/employees/{employee}`
  - **Blade Template**: `app/Modules/HR/resources/views/employees/show.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Edit Employee**
  - **Route Name**: `hr.employees.edit`
  - **URL**: `/hr/employees/{employee}/edit`
  - **Blade Template**: `app/Modules/HR/resources/views/employees/edit.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Departments Management**
  - **Route Name**: `hr.departments.index`
  - **URL**: `/hr/departments`
  - **Blade Template**: `app/Modules/HR/resources/views/departments/index.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Designations Management**
  - **Route Name**: `hr.designations.index`
  - **URL**: `/hr/designations`
  - **Blade Template**: `app/Modules/HR/resources/views/designations/index.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified

---

## 3. Operations (Time & Attendance Module)

- [x] **Attendance Logs & Check-ins**
  - **Route Name**: `attendance.index`
  - **URL**: `/attendance/logs`
  - **Blade Template**: `app/Modules/Attendance/resources/views/index.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Attendance Kiosk Mode**
  - **Route Name**: `attendance.kiosk`
  - **URL**: `/attendance/kiosk`
  - **Blade Template**: `app/Modules/Attendance/resources/views/kiosk.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Attendance Settings & Policies**
  - **Route Name**: `attendance.settings`
  - **URL**: `/attendance/settings`
  - **Blade Template**: `app/Modules/Attendance/resources/views/settings.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Create Attendance Record**
  - **Route Name**: `attendance.create`
  - **URL**: `/attendance/logs/create`
  - **Blade Template**: `app/Modules/Attendance/resources/views/create.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **View Attendance Record**
  - **Route Name**: `attendance.show`
  - **URL**: `/attendance/logs/{log}`
  - **Blade Template**: `app/Modules/Attendance/resources/views/show.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Shift Assignments**
  - **Route Name**: `attendance.shifts.assignments`
  - **URL**: `/attendance/shifts/assignments`
  - **Blade Template**: `app/Modules/Attendance/resources/views/shifts/assignments.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Calendar View Partial**
  - **Blade Template**: `app/Modules/Attendance/resources/views/partials/calendar_view.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified

---

## 4. Operations (Leaves Module)

- [x] **Leave Requests List**
  - **Route Name**: `leave.requests.index`
  - **URL**: `/leave/requests`
  - **Blade Template**: `app/Modules/Leave/resources/views/index.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Create Leave Request**
  - **Route Name**: `leave.requests.create`
  - **URL**: `/leave/requests/create`
  - **Blade Template**: `app/Modules/Leave/resources/views/create.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **View Leave Request**
  - **Route Name**: `leave.requests.show`
  - **URL**: `/leave/requests/{request}`
  - **Blade Template**: `app/Modules/Leave/resources/views/show.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Holidays Calendar**
  - **Route Name**: `leave.holidays.index`
  - **URL**: `/leave/holidays`
  - **Blade Template**: `app/Modules/Leave/resources/views/holidays/index.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Compensatory Offs (Comp-Off)**
  - **Route Name**: `leave.comp-off.index`
  - **URL**: `/leave/comp-off`
  - **Blade Template**: `app/Modules/Leave/resources/views/comp_off/index.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified
- [x] **Leave Types Settings**
  - **Route Name**: `leave.types.index`
  - **URL**: `/leave/settings/leave-types`
  - **Blade Template**: `app/Modules/Leave/resources/views/types/index.blade.php`
  - **Status**: [x] Layout Migrated | [x] Styled | [x] Verified

---

## 5. Operations (Payroll Module)

- [ ] **Payroll Dashboard & Runs**
  - **Route Name**: `payroll.index`
  - **URL**: `/payroll`
  - **Blade Template**: `app/Modules/Payroll/resources/views/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Create Payroll Run**
  - **Route Name**: `payroll.create`
  - **URL**: `/payroll/create`
  - **Blade Template**: `app/Modules/Payroll/resources/views/create.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **View Payroll Run Details**
  - **Route Name**: `payroll.show`
  - **URL**: `/payroll/{run}`
  - **Blade Template**: `app/Modules/Payroll/resources/views/show.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Salary Structures**
  - **Route Name**: `payroll.salary_structures.index`
  - **URL**: `/payroll/salary-structures`
  - **Blade Template**: `app/Modules/Payroll/resources/views/salary_structures/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Create Salary Structure**
  - **Route Name**: `payroll.salary_structures.create`
  - **URL**: `/payroll/salary-structures/create`
  - **Blade Template**: `app/Modules/Payroll/resources/views/salary_structures/create.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Payroll Component Settings**
  - **Route Name**: `payroll.components.index`
  - **URL**: `/payroll/settings/components`
  - **Blade Template**: `app/Modules/Payroll/resources/views/components/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Print Payslip Template**
  - **Blade Template**: `app/Modules/Payroll/resources/views/payslip_print.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified

---

## 6. Operations (Business/Projects Module)

- [ ] **Clients Management**
  - **Route Name**: `operations.clients.index`
  - **URL**: `/operations/clients`
  - **Blade Template**: `app/Modules/Operations/resources/views/clients/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Contacts Management**
  - **Route Name**: `operations.contacts.index`
  - **URL**: `/operations/contacts`
  - **Blade Template**: `app/Modules/Operations/resources/views/contacts/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Create Contact**
  - **Route Name**: `operations.contacts.create`
  - **URL**: `/operations/contacts/create`
  - **Blade Template**: `app/Modules/Operations/resources/views/contacts/create.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Edit Contact**
  - **Route Name**: `operations.contacts.edit`
  - **URL**: `/operations/contacts/{contact}/edit`
  - **Blade Template**: `app/Modules/Operations/resources/views/contacts/edit.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Leads Pipeline**
  - **Route Name**: `operations.leads.index`
  - **URL**: `/operations/leads`
  - **Blade Template**: `app/Modules/Operations/resources/views/leads/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Create Lead**
  - **Route Name**: `operations.leads.create`
  - **URL**: `/operations/leads/create`
  - **Blade Template**: `app/Modules/Operations/resources/views/leads/create.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **View Lead Details**
  - **Route Name**: `operations.leads.show`
  - **URL**: `/operations/leads/{lead}`
  - **Blade Template**: `app/Modules/Operations/resources/views/leads/show.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Edit Lead**
  - **Route Name**: `operations.leads.edit`
  - **URL**: `/operations/leads/{lead}/edit`
  - **Blade Template**: `app/Modules/Operations/resources/views/leads/edit.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Projects Management**
  - **Route Name**: `operations.projects.index`
  - **URL**: `/operations/projects`
  - **Blade Template**: `app/Modules/Operations/resources/views/projects/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Create Project**
  - **Route Name**: `operations.projects.create`
  - **URL**: `/operations/projects/create`
  - **Blade Template**: `app/Modules/Operations/resources/views/projects/create.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **View Project & Tasks**
  - **Route Name**: `operations.projects.show`
  - **URL**: `/operations/projects/{project}`
  - **Blade Template**: `app/Modules/Operations/resources/views/projects/show.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Tasks Backlog**
  - **Route Name**: `operations.tasks.index`
  - **URL**: `/operations/tasks`
  - **Blade Template**: `app/Modules/Operations/resources/views/tasks/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Create Task**
  - **Route Name**: `operations.tasks.create`
  - **URL**: `/operations/tasks/create`
  - **Blade Template**: `app/Modules/Operations/resources/views/tasks/create.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Edit Task**
  - **Route Name**: `operations.tasks.edit`
  - **URL**: `/operations/tasks/{task}/edit`
  - **Blade Template**: `app/Modules/Operations/resources/views/tasks/edit.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Timesheets Management**
  - **Route Name**: `operations.timesheets.index`
  - **URL**: `/operations/timesheets`
  - **Blade Template**: `app/Modules/Operations/timesheets/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified

---

## 7. Growth (Performance Module)

- [ ] **Performance Dashboard**
  - **Route Name**: `performance.dashboard`
  - **URL**: `/performance`
  - **Blade Template**: `app/Modules/Performance/resources/views/dashboard.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Goal Setting**
  - **Route Name**: `performance.goals.index`
  - **URL**: `/performance/goals`
  - **Blade Template**: `app/Modules/Performance/resources/views/goals/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **KPIs Setup**
  - **Route Name**: `performance.kpis.index`
  - **URL**: `/performance/kpis`
  - **Blade Template**: `app/Modules/Performance/resources/views/kpis/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Appraisals Management**
  - **Route Name**: `performance.appraisals.index`
  - **URL**: `/performance/appraisals`
  - **Blade Template**: `app/Modules/Performance/resources/views/appraisals/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified

---

## 8. Growth (Recruitment Module)

- [ ] **Recruitment Dashboard**
  - **Route Name**: `recruitment.dashboard`
  - **URL**: `/recruitment`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/job_postings/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Job Postings**
  - **Route Name**: `recruitment.job_postings.index`
  - **URL**: `/recruitment/job_postings`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/job_postings/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Create Job Posting**
  - **Route Name**: `recruitment.job_postings.create`
  - **URL**: `/recruitment/job_postings/create`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/job_postings/create.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **View Job Posting**
  - **Route Name**: `recruitment.job_postings.show`
  - **URL**: `/recruitment/job_postings/{job_posting}`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/job_postings/show.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Edit Job Posting**
  - **Route Name**: `recruitment.job_postings.edit`
  - **URL**: `/recruitment/job_postings/{job_posting}/edit`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/job_postings/edit.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Applications List**
  - **Route Name**: `recruitment.applications.index`
  - **URL**: `/recruitment/applications`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/applications/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **View Candidate Application**
  - **Route Name**: `recruitment.applications.show`
  - **URL**: `/recruitment/applications/{application}`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/applications/show.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Public Careers Page (List - Guest Access)**
  - **Route Name**: `tenant.careers.index`
  - **URL**: `/careers`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/public/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Public Job Details (Guest Access)**
  - **Route Name**: `tenant.careers.show`
  - **URL**: `/careers/{job_posting}`
  - **Blade Template**: `app/Modules/Recruitment/resources/views/public/show.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified

---

## 9. Analytics (Reports Module)

- [ ] **Reports Dashboard**
  - **Route Name**: `reports.index`
  - **URL**: `/reports`
  - **Blade Template**: `app/Modules/Reports/resources/views/index.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Workforce Report**
  - **Route Name**: `reports.workforce`
  - **URL**: `/reports/workforce`
  - **Blade Template**: `app/Modules/Reports/resources/views/workforce.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Attendance Report**
  - **Route Name**: `reports.attendance`
  - **URL**: `/reports/attendance`
  - **Blade Template**: `app/Modules/Reports/resources/views/attendance.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
- [ ] **Payroll Report**
  - **Route Name**: `reports.payroll`
  - **URL**: `/reports/payroll`
  - **Blade Template**: `app/Modules/Reports/resources/views/payroll.blade.php`
  - **Status**: [ ] Layout Migrated | [ ] Styled | [ ] Verified
