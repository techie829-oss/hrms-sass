<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Add New Employee</h2>
                <p class="text-sm opacity-70 mt-1">Register a new member in the organizational system.</p>
            </div>
            <a href="{{ route('hr.employees.index') }}" class="btn btn-ghost btn-sm">
                <span class="material-symbols-outlined">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6">
        <form action="{{ route('hr.employees.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Personal Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">First Name <span class="text-error">*</span></span></div>
                            <input type="text" name="first_name" required value="{{ old('first_name', $first_name ?? '') }}" class="input input-bordered w-full" placeholder="John" />
                            @error('first_name') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Last Name <span class="text-error">*</span></span></div>
                            <input type="text" name="last_name" required value="{{ old('last_name', $last_name ?? '') }}" class="input input-bordered w-full" placeholder="Doe" />
                            @error('last_name') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Gender</span></div>
                            <select name="gender" class="select select-bordered w-full">
                                <option value="" disabled selected>Select</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Date of Birth</span></div>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="input input-bordered w-full" />
                            @error('date_of_birth') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Company Email (Login ID) <span class="text-error">*</span></span></div>
                            <input type="email" name="email" required value="{{ old('email', $email ?? '') }}" class="input input-bordered w-full" placeholder="john@company.com" />
                            @error('email') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Personal Email</span></div>
                            <input type="email" name="personal_email" value="{{ old('personal_email') }}" class="input input-bordered w-full" placeholder="john@gmail.com" />
                            @error('personal_email') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Mobile Number</span></div>
                            <div class="flex gap-2">
                                <input type="text" name="country_code" value="{{ old('country_code', '+91') }}" class="input input-bordered w-24 text-center" placeholder="+91" />
                                <input type="text" name="phone" value="{{ old('phone') }}" class="input input-bordered w-full" placeholder="9876543210" />
                            </div>
                            @error('phone') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Employee ID <span class="text-error">*</span></span></div>
                            <input type="text" name="employee_id" required value="{{ old('employee_id') }}" class="input input-bordered w-full uppercase" placeholder="EMP-001" />
                            @error('employee_id') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>
                </div>
            </div>

            <!-- Employment Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-secondary">work</span>
                        Employment Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Joining Date <span class="text-error">*</span></span></div>
                            <input type="date" name="date_of_joining" required value="{{ old('date_of_joining') }}" class="input input-bordered w-full" />
                            @error('date_of_joining') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Department</span></div>
                            <select name="department_id" class="select select-bordered w-full">
                                <option disabled selected>Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $department_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Employment Type</span></div>
                            <select name="employment_type" class="select select-bordered w-full">
                                <option value="full_time" {{ old('employment_type', $employment_type ?? '') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ old('employment_type', $employment_type ?? '') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('employment_type', $employment_type ?? '') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="intern" {{ old('employment_type', $employment_type ?? '') == 'intern' ? 'selected' : '' }}>Intern</option>
                            </select>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Status</span></div>
                            <select name="status" class="select select-bordered w-full">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </label>

                        <div class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Monthly Basic Salary <span class="text-error">*</span></span></div>
                            <label class="input input-bordered flex items-center gap-2 w-full">
                                <span class="opacity-50 font-bold">₹</span>
                                <input type="number" step="0.01" name="basic_salary" required value="{{ old('basic_salary') }}" class="w-full" placeholder="0.00" />
                            </label>
                            @error('basic_salary') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </div>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Reporting To (Manager)</span></div>
                            <select name="reporting_to" class="select select-bordered w-full">
                                <option value="">None (Top Level)</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('reporting_to') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} ({{ $emp->employee_id }})</option>
                                @endforeach
                            </select>
                            @error('reporting_to') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>
                </div>
            </div>

            <!-- Identity & Documents Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-warning">badge</span>
                        Identity & Documents
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">PAN Number</span></div>
                            <input type="text" name="pan_number" value="{{ old('pan_number') }}" class="input input-bordered w-full uppercase" placeholder="ABCDE1234F" />
                            @error('pan_number') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Aadhar Number</span></div>
                            <input type="text" name="aadhar_number" value="{{ old('aadhar_number') }}" class="input input-bordered w-full" placeholder="1234 5678 9012" />
                            @error('aadhar_number') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Passport Number</span></div>
                            <input type="text" name="passport_number" value="{{ old('passport_number') }}" class="input input-bordered w-full uppercase" />
                            @error('passport_number') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-info">home</span>
                        Address Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Current Address</span></div>
                            <textarea name="current_address" class="textarea textarea-bordered h-24" placeholder="Enter current residential address">{{ old('current_address') }}</textarea>
                            @error('current_address') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Permanent Address</span></div>
                            <textarea name="permanent_address" class="textarea textarea-bordered h-24" placeholder="Enter permanent address">{{ old('permanent_address') }}</textarea>
                            @error('permanent_address') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-error">emergency</span>
                        Emergency Contact
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Contact Name</span></div>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="input input-bordered w-full" />
                            @error('emergency_contact_name') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Relationship</span></div>
                            <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}" class="input input-bordered w-full" placeholder="e.g. Spouse, Parent" />
                            @error('emergency_contact_relation') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Contact Number</span></div>
                            <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="input input-bordered w-full" />
                            @error('emergency_contact_phone') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>
                </div>
            </div>

            <!-- System Access Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-tertiary">manage_accounts</span>
                        System Access & Role
                    </h3>

                    <div class="flex items-start gap-4 bg-primary/5 p-4 rounded-xl border border-primary/10 mb-4">
                        <input type="hidden" name="create_login" value="0">
                        <input type="checkbox" id="create_login" name="create_login" value="1"
                            class="checkbox checkbox-primary mt-1"
                            onchange="document.getElementById('login_fields').classList.toggle('hidden', !this.checked)" />
                        <div>
                            <label for="create_login" class="font-bold text-sm cursor-pointer select-none text-primary">
                                Create Login Account
                            </label>
                            <p class="text-xs opacity-70 mt-0.5">
                                Enable system login access for this employee with a role and password.
                            </p>
                        </div>
                    </div>

                    <div id="login_fields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Assign Role <span class="text-error">*</span></span></div>
                            <select name="role_id" id="role_id" class="select select-bordered w-full">
                                <option value="" disabled selected>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Login Password <span class="text-error">*</span></span></div>
                            <input type="text" name="login_password" class="input input-bordered w-full" placeholder="Min 8 characters" value="password" />
                            <div class="label"><span class="label-text-alt opacity-60">Default: password (ask employee to change)</span></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-2 mt-8">
                <a href="{{ route('hr.employees.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span> Save Employee
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

