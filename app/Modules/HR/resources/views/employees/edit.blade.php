@php
use App\Modules\Attendance\Models\AttendanceEmployeeEnforcement;
use App\Core\Constants\PermissionConstants;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Edit Employee Profile</h2>
                <p class="text-xs text-slate-500 mt-1">Update information for {{ $employee->full_name }}.</p>
            </div>
            <a href="{{ route('hr.employees.show', $employee->id) }}" class="btn btn-ghost btn-sm border-outline-variant/20 rounded-xl px-4">
                <span class="material-symbols-outlined text-base">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6">
        <form action="{{ route('hr.employees.update', $employee->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Personal Section -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">First Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name" required value="{{ old('first_name', $employee->first_name) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm bg-white" />
                            @error('first_name') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="last_name" required value="{{ old('last_name', $employee->last_name) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm bg-white" />
                            @error('last_name') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gender</label>
                            <select name="gender" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                                <option value="" disabled selected>Select</option>
                                <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm bg-white" />
                            @error('date_of_birth') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Company Email (Login ID) <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required value="{{ old('email', $employee->email) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm bg-white" />
                            @error('email') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Personal Email</label>
                            <input type="email" name="personal_email" value="{{ old('personal_email', $employee->personal_email) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm bg-white" />
                            @error('personal_email') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mobile Number</label>
                            <div class="flex gap-2 mt-1">
                                <input type="text" name="country_code" value="{{ old('country_code', $employee->country_code ?? '+91') }}" class="w-24 text-center border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 bg-white transition-all shadow-sm" />
                                <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="flex-1 min-w-0 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 bg-white transition-all shadow-sm" />
                            </div>
                            @error('phone') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Employee ID <span class="text-rose-500">*</span></label>
                            <input type="text" name="employee_id" required value="{{ old('employee_id', $employee->employee_id) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm uppercase bg-white" />
                            @error('employee_id') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Section -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">work</span>
                        Employment Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Joining Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="date_of_joining" required value="{{ old('date_of_joining', $employee->date_of_joining?->format('Y-m-d')) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm bg-white" />
                            @error('date_of_joining') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Department</label>
                            <select name="department_id" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                                <option disabled selected>Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Designation</label>
                            <select name="designation_id" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                                <option disabled selected>Select Designation</option>
                                @foreach($designations as $desig)
                                    <option value="{{ $desig->id }}" {{ old('designation_id', $employee->designation_id) == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                                @endforeach
                            </select>
                            @error('designation_id') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Employment Type</label>
                            <select name="employment_type" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                                <option value="full_time" {{ old('employment_type', $employee->employment_type) === 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ old('employment_type', $employee->employment_type) === 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('employment_type', $employee->employment_type) === 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="intern" {{ old('employment_type', $employee->employment_type) === 'intern' ? 'selected' : '' }}>Intern</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status</label>
                            <select name="status" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                                <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="on_leave" {{ old('status', $employee->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                                <option value="resigned" {{ old('status', $employee->status) === 'resigned' ? 'selected' : '' }}>Resigned</option>
                            </select>
                        </div>

                        <div class="form-control w-full">
                            <div class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Monthly Basic Salary <span class="text-rose-500">*</span></div>
                            <div class="flex rounded-xl shadow-sm mt-1">
                                <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-sm font-bold">
                                    ₹
                                </span>
                                <input type="number" step="0.01" name="basic_salary" required value="{{ old('basic_salary', $employee->basic_salary) }}" class="flex-1 min-w-0 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 bg-white transition-all" />
                            </div>
                            @error('basic_salary') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reporting To (Manager)</label>
                            <select name="reporting_to" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                                <option value="">None (Top Level)</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('reporting_to', $employee->reporting_to) == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} ({{ $emp->employee_id }})</option>
                                @endforeach
                            </select>
                            @error('reporting_to') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identity & Documents Section -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-warning">badge</span>
                        Identity & Documents
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">PAN Number</label>
                            <input type="text" name="pan_number" value="{{ old('pan_number', $employee->pan_number) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm uppercase bg-white" placeholder="ABCDE1234F" />
                            @error('pan_number') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Aadhar Number</label>
                            <input type="text" name="aadhar_number" value="{{ old('aadhar_number', $employee->aadhar_number) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm bg-white" placeholder="1234 5678 9012" />
                            @error('aadhar_number') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Passport Number</label>
                            <input type="text" name="passport_number" value="{{ old('passport_number', $employee->passport_number) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm uppercase bg-white" />
                            @error('passport_number') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-info">home</span>
                        Address Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Current Address</label>
                            <textarea name="current_address" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm h-24 bg-white" placeholder="Enter current residential address">{{ old('current_address', $employee->current_address) }}</textarea>
                            @error('current_address') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Permanent Address</label>
                            <textarea name="permanent_address" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm h-24 bg-white" placeholder="Enter permanent address">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                            @error('permanent_address') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Section -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-error">emergency</span>
                        Emergency Contact
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contact Name</label>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm bg-white" />
                            @error('emergency_contact_name') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Relationship</label>
                            <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $employee->emergency_contact_relation) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 placeholder-slate-400/75 transition-all shadow-sm bg-white" placeholder="e.g. Spouse, Parent" />
                            @error('emergency_contact_relation') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contact Number</label>
                            <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 transition-all shadow-sm bg-white" />
                            @error('emergency_contact_phone') <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role & System Settings -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-4 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">settings</span>
                        Role & System Settings
                    </h3>
                    
                    @if($employee->user_id && $employee->user)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="form-control w-full">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">User Role <span class="text-rose-500">*</span></label>
                                <select name="role_id" id="role_id" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                                    <option value="" disabled>Select Role</option>
                                    @php
                                        $userRoleId = $employee->user->roles->first()?->id;
                                    @endphp
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $userRoleId) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @php
                            $userPermissions = $employee->user->permissions->pluck('name')->toArray();
                            $permissionDetails = PermissionConstants::getPermissionDetails();
                            $dbPermissions = $permissions->pluck('name')->toArray();
                        @endphp
                        <div class="col-span-2 mb-6">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Direct User Permissions (Optional)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50/50 p-6 rounded-xl border border-slate-200">
                                @foreach($permissionDetails as $group => $perms)
                                    <div class="space-y-2">
                                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide border-b border-slate-200/60 pb-1.5">{{ $group }}</h4>
                                        <div class="space-y-1.5">
                                            @foreach($perms as $rawName => $label)
                                                @if(in_array($rawName, $dbPermissions))
                                                    <label class="flex items-center text-xs text-slate-700 select-none cursor-pointer hover:text-slate-900 transition-colors w-full">
                                                        <input type="checkbox" name="permissions[]" value="{{ $rawName }}" {{ in_array($rawName, old('permissions', $userPermissions)) ? 'checked' : '' }} class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500 mr-2 permission-checkbox" />
                                                        <span class="flex-1 truncate">{{ $label }}</span>
                                                        <span class="ml-2 text-[9px] font-bold text-slate-500 bg-slate-200/80 px-1.5 py-0.5 rounded hidden role-badge">Role Default</span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-500 mt-2">Check to assign specific permissions directly to this user, bypassing their role settings.</p>
                        </div>
                    @else
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

                        <div id="login_fields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                                $permissionDetails = PermissionConstants::getPermissionDetails();
                                $dbPermissions = $permissions->pluck('name')->toArray();
                            @endphp
                            <div class="col-span-2 mt-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Direct User Permissions (Optional)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50/50 p-6 rounded-xl border border-slate-200">
                                    @foreach($permissionDetails as $group => $perms)
                                        <div class="space-y-2">
                                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide border-b border-slate-200/60 pb-1.5">{{ $group }}</h4>
                                            <div class="space-y-1.5">
                                                @foreach($perms as $rawName => $label)
                                                    @if(in_array($rawName, $dbPermissions))
                                                        <label class="flex items-center text-xs text-slate-700 select-none cursor-pointer hover:text-slate-900 transition-colors w-full">
                                                            <input type="checkbox" name="permissions[]" value="{{ $rawName }}" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500 mr-2 permission-checkbox" />
                                                            <span class="flex-1 truncate">{{ $label }}</span>
                                                            <span class="ml-2 text-[9px] font-bold text-slate-500 bg-slate-200/80 px-1.5 py-0.5 rounded hidden role-badge">Role Default</span>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-[10px] text-slate-500 mt-2">Check to assign specific permissions directly to this user, bypassing their role settings.</p>
                            </div>
                        </div>
                    @endif

                    @php
                        $employeeEnforcement = AttendanceEmployeeEnforcement::where('employee_id', $employee->id)->first();
                        $currentCheckinRequired = $employeeEnforcement ? ($employeeEnforcement->enforce_kiosk == 1 ? '1' : '0') : '';
                    @endphp
                    <div class="form-control w-full">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Daily Clock-In Enforcement</label>
                        <select name="checkin_required" class="mt-1 block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-1.5 text-sm text-gray-900 bg-white transition-all shadow-sm">
                            <option value="" {{ old('checkin_required', $currentCheckinRequired) === '' ? 'selected' : '' }}>Default (Inherit from Company/Role)</option>
                            <option value="1" {{ old('checkin_required', $currentCheckinRequired) === '1' ? 'selected' : '' }}>Force Require Clock-In</option>
                            <option value="0" {{ old('checkin_required', $currentCheckinRequired) === '0' ? 'selected' : '' }}>Exempt (Bypass Enforcement)</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-2">
                            Control whether this specific employee is forced to clock-in or exempted, regardless of general system defaults.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('hr.employees.show', $employee->id) }}" class="inline-flex justify-center py-2 px-4 border border-slate-300 rounded-xl shadow-sm text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

@php
    $rolePermMap = [];
    foreach($roles as $role) {
        $rolePermMap[$role->id] = $role->permissions->pluck('name')->toArray();
    }
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleMap = @json($rolePermMap);
        const roleSelect = document.getElementById('role_id');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        function updatePermissions() {
            if (!roleSelect) return;
            const roleId = roleSelect.value;
            const rolePerms = roleMap[roleId] || [];

            checkboxes.forEach(cb => {
                const badge = cb.closest('label').querySelector('.role-badge');
                
                if (rolePerms.includes(cb.value)) {
                    cb.checked = true;
                    // We don't disable it completely so they can see it, but it's logically "readonly" 
                    // Wait, if it's disabled, it doesn't submit, which is EXACTLY what we want (so it doesn't become a direct permission)
                    cb.disabled = true; 
                    cb.parentElement.classList.add('opacity-60');
                    if (badge) badge.classList.remove('hidden');
                } else {
                    if (cb.disabled) {
                        cb.checked = false;
                        cb.disabled = false;
                        cb.parentElement.classList.remove('opacity-60');
                        if (badge) badge.classList.add('hidden');
                    }
                }
            });
        }

        if (roleSelect) {
            roleSelect.addEventListener('change', updatePermissions);
            updatePermissions();
        }
    });
</script>
