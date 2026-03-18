<x-careers-layout>
    <div class="min-h-screen bg-surface-container-lowest">
        <!-- Hero Section -->
        <div class="bg-primary/5 border-b border-outline-variant/15 py-12">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-6">
                    <div>
                        <a href="{{ route('tenant.careers.index') }}" class="inline-flex items-center text-primary font-bold text-sm mb-6 hover:underline">
                            <span class="material-symbols-outlined text-[16px] mr-1">arrow_back</span> Back to Jobs
                        </a>
                        <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-on-surface leading-tight">{{ $job_posting->title }}</h1>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-on-surface-variant">
                    <div class="flex items-center gap-1.5 bg-surface-container-highest px-3 py-1.5 rounded-lg border border-outline-variant/30 text-on-surface">
                        <span class="material-symbols-outlined text-[18px] text-primary">business_center</span>
                        {{ ucwords(str_replace('_', ' ', $job_posting->employment_type)) }}
                    </div>
                    <div class="flex items-center gap-1.5 bg-surface-container-highest px-3 py-1.5 rounded-lg border border-outline-variant/30 text-on-surface">
                        <span class="material-symbols-outlined text-[18px] text-secondary">location_on</span>
                        {{ $job_posting->location ?? 'Remote / Flexible' }}
                    </div>
                    @if($job_posting->salary_range)
                        <div class="flex items-center gap-1.5 bg-surface-container-highest px-3 py-1.5 rounded-lg border border-outline-variant/30 text-on-surface">
                            <span class="material-symbols-outlined text-[18px] text-success">payments</span>
                            {!! $job_posting->salary_range !!}
                        </div>
                    @endif
                    <div class="flex items-center gap-1.5 bg-surface-container-highest px-3 py-1.5 rounded-lg border border-outline-variant/30 text-on-surface">
                        <span class="material-symbols-outlined text-[18px] text-accent">schedule</span>
                        Posted {{ $job_posting->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="max-w-7xl mx-auto px-6 py-12">
            
            @if(session('success'))
                <div class="alert alert-success mb-10 border border-success/20 bg-success/10 text-success-content rounded-xl">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
                
                <!-- Job Description Column -->
                <div class="lg:col-span-3 space-y-10">
                    <section class="prose prose-sm md:prose-base max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-a:text-primary hover:prose-a:text-primary-focus">
                        <h2 class="text-2xl font-bold text-on-surface mb-4 border-b border-outline-variant/20 pb-2">About the Role</h2>
                        <div class="text-on-surface-variant font-medium leading-relaxed">
                            {!! nl2br(e($job_posting->description)) !!}
                        </div>
                    </section>
                    
                    @if($job_posting->requirements)
                        <section class="prose prose-sm md:prose-base max-w-none">
                            <h2 class="text-2xl font-bold text-on-surface mb-4 border-b border-outline-variant/20 pb-2">Requirements</h2>
                            <div class="text-on-surface-variant font-medium leading-relaxed whitespace-pre-wrap">{!! nl2br(e($job_posting->requirements)) !!}</div>
                        </section>
                    @endif
                </div>

                <!-- Application Form Sidebar -->
                <div class="lg:col-span-2">
                    <div class="card bg-surface-container shadow-xl border border-outline-variant/30 sticky top-24">
                        <div class="card-body p-6">
                            <h3 class="text-xl font-bold mb-6 text-on-surface">Apply for this Position</h3>
                            
                            @if ($errors->any())
                                <div class="alert alert-error mb-6 py-2 px-3 text-sm rounded-lg border border-error/50 bg-error/10 text-error">
                                    <ul class="list-disc list-inside font-medium">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('tenant.careers.store', ['job_posting' => $hash]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase tracking-widest text-on-surface-variant">First Name <span class="text-error">*</span></span></label>
                                    <input type="text" name="first_name" required value="{{ old('first_name') }}" class="input input-bordered w-full input-sm sm:input-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Jane" />
                                </div>

                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase tracking-widest text-on-surface-variant">Last Name <span class="text-error">*</span></span></label>
                                    <input type="text" name="last_name" required value="{{ old('last_name') }}" class="input input-bordered w-full input-sm sm:input-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Doe" />
                                </div>

                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase tracking-widest text-on-surface-variant">Email Address <span class="text-error">*</span></span></label>
                                    <input type="email" name="email" required value="{{ old('email') }}" class="input input-bordered w-full input-sm sm:input-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="jane.doe@example.com" />
                                </div>

                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase tracking-widest text-on-surface-variant">Phone Number</span></label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" class="input input-bordered w-full input-sm sm:input-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="+1 (555) 000-0000" />
                                </div>

                                <div class="form-control mt-2">
                                    <label class="label py-1">
                                        <span class="label-text font-bold text-xs uppercase tracking-widest text-on-surface-variant">Resume (PDF/Doc)</span>
                                    </label>
                                    <input type="file" name="resume" accept=".pdf,.doc,.docx" class="file-input file-input-bordered file-input-primary w-full file-input-sm sm:file-input-md" />
                                    <label class="label py-1"><span class="label-text-alt opacity-70">Max size: 5MB</span></label>
                                </div>
                                
                                <div class="form-control mt-2">
                                    <label class="label py-1"><span class="label-text font-bold text-xs uppercase tracking-widest text-on-surface-variant">Cover Letter (Optional)</span></label>
                                    <textarea name="cover_letter" class="textarea textarea-bordered w-full h-24 focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Tell us why you're a great fit...">{{ old('cover_letter') }}</textarea>
                                </div>

                                <div class="mt-8">
                                    <button type="submit" class="btn btn-primary w-full shadow-lg shadow-primary/30 group">
                                        Submit Application
                                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">send</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-careers-layout>
