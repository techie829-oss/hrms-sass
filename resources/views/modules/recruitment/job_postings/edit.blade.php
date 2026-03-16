<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Edit Job Posting</h2>
                <p class="text-sm opacity-70 mt-1">{{ $posting->title }}</p>
            </div>
            <a href="{{ route('recruitment.job_postings.index') }}" class="btn btn-ghost btn-sm">
                <span class="material-symbols-outlined">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6">
        <form action="{{ route('recruitment.job_postings.update', $posting->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Details -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-primary">work</span>
                        Basic Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="form-control w-full md:col-span-2">
                            <div class="label"><span class="label-text font-bold">Job Title <span class="text-error">*</span></span></div>
                            <input type="text" name="title" value="{{ old('title', $posting->title) }}" required class="input input-bordered w-full" placeholder="e.g., Senior Software Engineer" />
                            @error('title') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Department/Location</span></div>
                            <input type="text" name="location" value="{{ old('location', $posting->location) }}" class="input input-bordered w-full" placeholder="e.g., San Francisco, CA / Remote" />
                            @error('location') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                        
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Employment Type <span class="text-error">*</span></span></div>
                            <select name="employment_type" required class="select select-bordered w-full">
                                <option disabled selected>Select employment type</option>
                                <option value="full_time" {{ old('employment_type', $posting->employment_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ old('employment_type', $posting->employment_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('employment_type', $posting->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="intern" {{ old('employment_type', $posting->employment_type) == 'intern' ? 'selected' : '' }}>Internship</option>
                            </select>
                            @error('employment_type') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-secondary">description</span>
                        Description & Requirements
                    </h3>

                    <div class="space-y-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Job Description <span class="text-error">*</span></span></div>
                            <textarea name="description" required rows="6" class="textarea textarea-bordered h-32 w-full" placeholder="Describe the role, responsibilities, and team...">{{ old('description', $posting->description) }}</textarea>
                            @error('description') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Requirements / Qualifications</span></div>
                            <textarea name="requirements" rows="5" class="textarea textarea-bordered h-24 w-full" placeholder="List required skills, experience, and education...">{{ old('requirements', $posting->requirements) }}</textarea>
                            @error('requirements') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>
                </div>
            </div>

            <!-- Parameters Section -->
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h3 class="card-title text-lg border-b border-base-200 pb-2 mb-4">
                        <span class="material-symbols-outlined text-accent">settings</span>
                        Listing Parameters
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Salary Range</span></div>
                            <input type="text" name="salary_range" value="{{ old('salary_range', $posting->salary_range) }}" class="input input-bordered w-full" placeholder="e.g., $90k - $120k" />
                            @error('salary_range') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Closing Date</span></div>
                            <input type="date" name="closing_date" value="{{ old('closing_date', $posting->closing_date ? $posting->closing_date->format('Y-m-d') : '') }}" class="input input-bordered w-full" />
                            @error('closing_date') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>

                        <label class="form-control w-full">
                            <div class="label"><span class="label-text font-bold">Status <span class="text-error">*</span></span></div>
                            <select name="status" required class="select select-bordered w-full">
                                <option value="draft" {{ old('status', $posting->status) == 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                                <option value="open" {{ old('status', $posting->status) == 'open' ? 'selected' : '' }}>Open (Accepting Applicants)</option>
                                <option value="closed" {{ old('status', $posting->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status') <div class="label"><span class="label-text-alt text-error">{{ $message }}</span></div> @enderror
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-2 mt-8">
                <a href="{{ route('recruitment.job_postings.show', $posting->id) }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined">save</span> Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
