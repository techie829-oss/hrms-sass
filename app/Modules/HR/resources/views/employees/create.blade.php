<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('hr.employees.index') }}" class="p-2 -ml-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Add New Employee</h2>
                <p class="text-xs text-slate-500 mt-1">Register a new member in the organizational system.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto pb-12">
        <form action="{{ route('hr.employees.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Personal Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600 shadow-sm border border-primary-100/50">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Personal Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">First Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="first_name" required value="{{ old('first_name', $first_name ?? '') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm" placeholder="John" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="last_name" required value="{{ old('last_name', $last_name ?? '') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm" placeholder="Doe" />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gender</label>
                        <select name="gender" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option value="" disabled selected>Select</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm bg-white" />
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Company Email (Login ID) <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email', $email ?? '') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm" placeholder="john@company.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Personal Email</label>
                        <input type="email" name="personal_email" value="{{ old('personal_email') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm" placeholder="john@gmail.com" />
                        <x-input-error :messages="$errors->get('personal_email')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mobile Number</label>
                        <div class="flex rounded-xl shadow-sm mt-1">
                            <input type="text" name="country_code" value="{{ old('country_code', '+91') }}" class="w-24 text-center rounded-none rounded-l-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 bg-white" placeholder="+91" />
                            <input type="text" name="phone" value="{{ old('phone') }}" class="flex-1 min-w-0 block w-full rounded-none rounded-r-xl border border-slate-200 border-l-0 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 bg-white" placeholder="9876543210" />
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Employee ID <span class="text-rose-500">*</span></label>
                        <input type="text" name="employee_id" required value="{{ old('employee_id') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm uppercase" placeholder="EMP-001" />
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Employment Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-700 shadow-sm border border-blue-100/50">
                        <span class="material-symbols-outlined text-[20px]">work</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Employment Details</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Joining Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="date_of_joining" required value="{{ old('date_of_joining') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm bg-white" />
                        <x-input-error :messages="$errors->get('date_of_joining')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department</label>
                        <select name="department_id" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option disabled selected>Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Designation</label>
                        <select name="designation_id" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option disabled selected>Select Designation</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->id }}" {{ old('designation_id') == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('designation_id')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Employment Type</label>
                        <select name="employment_type" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option value="full_time" {{ old('employment_type', $employment_type ?? '') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ old('employment_type', $employment_type ?? '') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('employment_type', $employment_type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="intern" {{ old('employment_type', $employment_type ?? '') == 'intern' ? 'selected' : '' }}>Intern</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Monthly Basic Salary <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-xl shadow-sm mt-1">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-sm font-bold">
                                ₹
                            </span>
                            <input type="number" step="0.01" name="basic_salary" required value="{{ old('basic_salary') }}" class="flex-1 min-w-0 block w-full rounded-none rounded-r-xl border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 bg-white transition-all" placeholder="0.00" />
                        </div>
                        <x-input-error :messages="$errors->get('basic_salary')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reporting To (Manager)</label>
                        <select name="reporting_to" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option value="">None (Top Level)</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('reporting_to') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('reporting_to')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Identity & Documents Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm border border-amber-100/50">
                        <span class="material-symbols-outlined text-[20px]">badge</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Identity & Documents</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PAN Number</label>
                        <input type="text" name="pan_number" value="{{ old('pan_number') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm uppercase" placeholder="ABCDE1234F" />
                        <x-input-error :messages="$errors->get('pan_number')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Aadhar Number</label>
                        <input type="text" name="aadhar_number" value="{{ old('aadhar_number') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm" placeholder="1234 5678 9012" />
                        <x-input-error :messages="$errors->get('aadhar_number')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Passport Number</label>
                        <input type="text" name="passport_number" value="{{ old('passport_number') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm uppercase" />
                        <x-input-error :messages="$errors->get('passport_number')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100/50">
                        <span class="material-symbols-outlined text-[20px]">home</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Address Details</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Current Address</label>
                        <textarea name="current_address" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm h-24 bg-white" placeholder="Enter current residential address">{{ old('current_address') }}</textarea>
                        <x-input-error :messages="$errors->get('current_address')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Permanent Address</label>
                        <textarea name="permanent_address" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm h-24 bg-white" placeholder="Enter permanent address">{{ old('permanent_address') }}</textarea>
                        <x-input-error :messages="$errors->get('permanent_address')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 shadow-sm border border-rose-100/50">
                        <span class="material-symbols-outlined text-[20px]">emergency</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Emergency Contact</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm bg-white" />
                        <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Relationship</label>
                        <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm bg-white" placeholder="e.g. Spouse, Parent" />
                        <x-input-error :messages="$errors->get('emergency_contact_relation')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contact Number</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm bg-white" />
                        <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- System Access Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-sm border border-teal-100/50">
                        <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">System Access & Role</h3>
                </div>

                <div class="flex items-start gap-4 bg-primary-50/50 p-5 rounded-xl border border-primary-100 mb-6">
                    <input type="hidden" name="create_login" value="0">
                    <input type="checkbox" id="create_login" name="create_login" value="1"
                        class="mt-1 rounded border-primary-300 text-primary-600 shadow-sm focus:ring-primary-500"
                        onchange="document.getElementById('login_fields').classList.toggle('hidden', !this.checked)" />
                    <div>
                        <label for="create_login" class="font-semibold text-sm cursor-pointer select-none text-primary-900">
                            Create Login Account
                        </label>
                        <p class="text-xs text-primary-700/70 mt-1">
                            Enable system login access for this employee with a role and password.
                        </p>
                    </div>
                </div>

                <div id="login_fields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assign Role <span class="text-rose-500">*</span></label>
                        <select name="role_id" id="role_id" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option value="" disabled selected>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Login Password <span class="text-rose-500">*</span></label>
                        <input type="text" name="login_password" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm" placeholder="Min 8 characters" value="password" />
                        <p class="mt-2 text-xs text-slate-500">Default: password (ask employee to change)</p>
                    </div>

                    @php
                        $groupedPermissions = $permissions->groupBy(function($permission) {
                            $parts = explode('_', $permission->name);
                            return $parts[1] ?? 'general';
                        });
                    @endphp
                    <div class="col-span-2 mt-6">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Direct User Permissions (Optional)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50/50 p-6 rounded-xl border border-slate-200">
                            @foreach($groupedPermissions as $group => $groupPerms)
                                <div class="space-y-2">
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide border-b border-slate-200/60 pb-1.5">{{ ucfirst($group) }}</h4>
                                    <div class="space-y-1.5">
                                        @foreach($groupPerms as $perm)
                                            <label class="flex items-center text-xs text-slate-700 select-none cursor-pointer hover:text-slate-900 transition-colors">
                                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500 mr-2" />
                                                {{ ucfirst($perm->name) }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-slate-500 mt-2">Check to assign specific permissions directly to this user, bypassing their role settings.</p>
                    </div>
                </div>
            </div>

            <!-- Role & System Settings -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-8">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">settings</span>
                        Role & System Settings
                    </h3>
                    <div class="form-control w-full">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Daily Clock-In Enforcement</label>
                        <select name="checkin_required" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option value="" {{ old('checkin_required') === null || old('checkin_required') === '' ? 'selected' : '' }}>Default (Inherit from Company/Role)</option>
                            <option value="1" {{ old('checkin_required') === '1' ? 'selected' : '' }}>Force Require Clock-In</option>
                            <option value="0" {{ old('checkin_required') === '0' ? 'selected' : '' }}>Exempt (Bypass Enforcement)</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-2">
                            Control whether this specific employee is forced to clock-in or exempted, regardless of general system defaults.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('hr.employees.index') }}" class="inline-flex justify-center py-2 px-4 border border-slate-300 rounded-xl shadow-sm text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Save Employee
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
