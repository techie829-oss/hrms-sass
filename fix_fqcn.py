import os
import re

files_to_fix = [
    "app/Modules/Attendance/resources/views/index.blade.php",
    "app/Modules/Recruitment/resources/views/job_postings/index.blade.php",
    "app/Modules/Operations/resources/views/timesheets/index.blade.php",
    "app/Modules/Operations/resources/views/projects/show.blade.php",
    "app/Modules/HR/resources/views/employees/edit.blade.php",
    "app/Modules/HR/resources/views/employees/create.blade.php",
    "app/Modules/HR/Requests/StoreDesignationRequest.php",
    "app/Modules/HR/Requests/StoreDepartmentRequest.php",
    "app/Modules/HR/Requests/UpdateDepartmentRequest.php",
    "app/Modules/HR/Requests/UpdateDesignationRequest.php",
    "app/Modules/Performance/resources/views/goals/index.blade.php",
    "app/Modules/Performance/resources/views/kpis/index.blade.php",
    "app/Modules/Performance/resources/views/dashboard.blade.php",
    "app/Modules/Performance/resources/views/appraisals/index.blade.php",
    "app/Modules/Leave/resources/views/index.blade.php",
    "app/Modules/Leave/resources/views/holidays/index.blade.php",
    "app/Modules/Leave/resources/views/comp_off/index.blade.php",
    "app/Modules/Leave/resources/views/show.blade.php",
    "app/SaaS/Billing/Subscription.php"
]

for filepath in files_to_fix:
    if not os.path.exists(filepath):
        continue
        
    with open(filepath, 'r') as f:
        content = f.read()

    uses = set()
    
    replacements = {
        r'\App\Core\Constants\PermissionConstants': 'PermissionConstants',
        r'\App\Modules\Operations\Models\Project': 'Project',
        r'\App\Modules\HR\Models\Employee': 'Employee',
        r'\App\Modules\Attendance\Models\AttendanceEmployeeEnforcement': 'AttendanceEmployeeEnforcement',
        r'\App\Modules\Performance\Models\Goal': 'Goal',
        r'\App\Modules\Performance\Models\KPI': 'KPI',
        r'\App\Modules\Performance\Models\Appraisal': 'Appraisal',
        r'\App\Models\Tenant': 'Tenant'
    }

    for fqcn, short_name in replacements.items():
        if fqcn in content:
            uses.add(fqcn.lstrip('\\'))
            content = content.replace(fqcn, short_name)

    if filepath.endswith('.blade.php') and uses:
        php_block = "@php\n"
        for use_stmt in uses:
            php_block += f"use {use_stmt};\n"
        php_block += "@endphp\n\n"
        
        if '@extends' in content:
            content = re.sub(r'(@extends\([^\)]+\)\n)', lambda m: m.group(1) + php_block, content, count=1)
        else:
            content = php_block + content

    elif filepath.endswith('.php') and uses:
        use_block = "\n"
        for use_stmt in uses:
            use_block += f"use {use_stmt};\n"
            
        content = re.sub(r'(namespace [^;]+;)', lambda m: m.group(1) + use_block, content, count=1)

    with open(filepath, 'w') as f:
        f.write(content)

print("Done replacing.")
