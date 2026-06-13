import os
import re

directories = ['app', 'database', 'resources', 'routes']
pattern = re.compile(r"(view|create|edit|delete|manage|approve|cancel|reject|export|import|generate)-(dashboard|employee|employees|department|departments|attendance|leave|leaves|payroll|project|projects|task|tasks|timesheet|performance|recruitment|report|reports|settings|setting|holiday|holidays|comp-off|saas-analytics|own-leave|own-attendance|own-timesheet|own-performance|travel)")

def process_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()
    
    new_content, count = pattern.subn(lambda m: m.group(1) + '_' + m.group(2), content)
    
    if count > 0:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print(f"Updated {count} occurrence(s) in {filepath}")

for directory in directories:
    for root, _, files in os.walk(directory):
        for file in files:
            if file.endswith('.php') or file.endswith('.blade.php'):
                process_file(os.path.join(root, file))

print("Done fixing codebase permissions!")
