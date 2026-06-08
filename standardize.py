import os
import re

directories = [
    'app/Modules/Recruitment/resources/views/public',
    'app/Modules/Recruitment/resources/views/applications',
    'app/Modules/Recruitment/resources/views/job_postings',
    'app/Modules/Operations/resources/views/timesheets',
    'app/Modules/Operations/resources/views/clients',
    'app/Modules/Operations/resources/views/tasks',
    'app/Modules/Operations/resources/views/leads',
    'app/Modules/Operations/resources/views/projects',
    'app/Modules/Operations/resources/views/contacts',
    'app/Modules/Payroll/resources/views',
    'app/Modules/Payroll/resources/views/salary_structures',
    'app/Modules/Payroll/resources/views/components',
    'app/Modules/Performance/resources/views/goals',
    'app/Modules/Performance/resources/views/kpis',
    'app/Modules/Performance/resources/views/appraisals',
    'app/Modules/Reports/resources/views'
]

for d in directories:
    file_path = os.path.join('/Users/rohitk/react/lara/hrms/hrms-sass', d, 'index.blade.php')
    if not os.path.exists(file_path):
        continue
        
    with open(file_path, 'r') as f:
        content = f.read()
        
    # Replace headers
    content = re.sub(r'<h2 class="text-xl font-bold[^"]*">', r'<h2 class="text-xl font-bold text-on-surface tracking-tight">', content)
    content = re.sub(r'<p class="text-xs font-medium mt-1 opacity-70">', r'<p class="text-xs font-medium mt-0.5 text-on-surface-variant">', content)
    
    # Replace buttons in header
    content = re.sub(r'<button([^>]*)class="btn btn-primary btn-sm"', r'<button\1class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20"', content)
    content = re.sub(r'<a([^>]*)class="btn btn-primary btn-sm"', r'<a\1class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20"', content)
    
    # Replace cards/tables
    content = re.sub(r'<div class="card bg-base-100 shadow-xl border border-base-200">', r'<div class="card-crm">', content)
    content = re.sub(r'<div class="bg-base-100 rounded-3xl shadow-sm border border-base-200 overflow-hidden">', r'<div class="table-crm">', content)
    content = re.sub(r'<div class="card bg-base-100 shadow-sm border border-base-200/60 rounded-3xl overflow-visible">', r'<div class="card-crm overflow-visible">', content)
    content = re.sub(r'<div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">', r'<div class="table-crm">', content)
    
    with open(file_path, 'w') as f:
        f.write(content)

print("Standardized remaining index.blade.php files")
