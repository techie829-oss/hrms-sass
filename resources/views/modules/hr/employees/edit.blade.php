<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Edit Employee Profile</h2>
                <p class="text-sm opacity-70 mt-1">Update information for {{ $employee->full_name }}.</p>
            </div>
            <a href="{{ route('hr.employees.show', $employee->id) }}" class="btn btn-ghost btn-sm">
                <span class="material-symbols-outlined">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6">
        <form action="{{ route('hr.employees.update', $employee->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Personal Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-primary">person</span>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">First Name <span class="text-error">*</span></span></div>
                            <input type="text" name="first_name" required value="{{ old('first_name', $employee->first_name) }}" class="input input-bordered w-full" />
                            @error('first_name') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Last Name <span class="text-error">*</span></span></div>
                            <input type="text" name="last_name" required value="{{ old('last_name', $employee->last_name) }}" class="input input-bordered w-full" />
                            @error('last_name') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Email Address <span class="text-error">*</span></span></div>
                            <input type="email" name="email" required value="{{ old('email', $employee->email) }}" class="input input-bordered w-full" />
                            @error('email') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Employee ID <span class="text-error">*</span></span></div>
                            <input type="text" name="employee_id" required value="{{ old('employee_id', $employee->employee_id) }}" class="input input-bordered w-full uppercase" />
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
                            <input type="date" name="date_of_joining" required value="{{ old('date_of_joining', $employee->date_of_joining?->format('Y-m-d')) }}" class="input input-bordered w-full" />
                            @error('date_of_joining') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Department</span></div>
                            <select name="department_id" class="select select-bordered w-full">
                                <option disabled selected>Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Employment Type</span></div>
                            <select name="employment_type" class="select select-bordered w-full">
                                <option value="full_time" {{ old('employment_type', $employee->employment_type) === 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ old('employment_type', $employee->employment_type) === 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('employment_type', $employee->employment_type) === 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="intern" {{ old('employment_type', $employee->employment_type) === 'intern' ? 'selected' : '' }}>Intern</option>
                            </select>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Status</span></div>
                            <select name="status" class="select select-bordered w-full">
                                <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="on_leave" {{ old('status', $employee->status) === 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="terminated" {{ old('status', $employee->status) === 'terminated' ? 'selected' : '' }}>Terminated</option>
                                <option value="resigned" {{ old('status', $employee->status) === 'resigned' ? 'selected' : '' }}>Resigned</option>
                            </select>
                        </label>

                        <div class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Monthly Basic Salary <span class="text-error">*</span></span></div>
                            <label class="input input-bordered flex items-center gap-2 w-full">
                                <span class="opacity-50 font-bold">₹</span>
                                <input type="number" step="0.01" name="basic_salary" required value="{{ old('basic_salary', $employee->basic_salary) }}" class="w-full" />
                            </label>
                            @error('basic_salary') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-2 mt-8">
                <a href="{{ route('hr.employees.show', $employee->id) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span> Update Employee
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
