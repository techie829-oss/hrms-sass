<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard Overview') }}
    </x-slot>

    <div class="space-y-10">
        <!-- DaisyUI Stats Section -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="stats shadow-xl bg-surface-container-lowest border border-outline-variant/15 rounded-[2rem] overflow-hidden lg:col-span-3">
                <div class="stat p-8">
                    <div class="stat-figure text-primary">
                        <span class="material-symbols-outlined text-4xl">groups</span>
                    </div>
                    <div class="stat-title font-bold uppercase tracking-widest text-[10px] text-on-surface-variant">Total Workforce</div>
                    <div class="stat-value text-primary font-headline text-4xl">1,240</div>
                    <div class="stat-desc font-medium mt-1">↗︎ 40 (14%) than last month</div>
                </div>
                
                <div class="stat p-8">
                    <div class="stat-figure text-secondary">
                        <span class="material-symbols-outlined text-4xl">event_busy</span>
                    </div>
                    <div class="stat-title font-bold uppercase tracking-widest text-[10px] text-on-surface-variant">Daily Attendance</div>
                    <div class="stat-value text-secondary font-headline text-4xl">94.2%</div>
                    <div class="stat-desc font-medium mt-1">↗︎ 2% improvement this week</div>
                </div>
                
                <div class="stat p-8">
                    <div class="stat-figure text-tertiary">
                        <span class="material-symbols-outlined text-4xl">assignment_turned_in</span>
                    </div>
                    <div class="stat-title font-bold uppercase tracking-widest text-[10px] text-on-surface-variant">Tasks Completed</div>
                    <div class="stat-value text-tertiary font-headline text-4xl">86%</div>
                    <div class="stat-desc font-medium mt-1">Remaining tasks: 12</div>
                </div>
            </div>

            <!-- Profile Completion (DaisyUI Radial Progress) -->
            <div class="bg-primary p-8 rounded-[2rem] premium-shadow text-on-primary primary-gradient flex flex-col items-center justify-center text-center space-y-4">
                <div class="radial-progress text-on-primary border-4 border-on-primary/20" style="--value:75; --size:6rem; --thickness: 6px;" role="progressbar">75%</div>
                <div>
                    <p class="font-bold text-sm uppercase tracking-widest font-label">Setup Status</p>
                    <p class="text-xs opacity-80">Finish company profile</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-surface-container-lowest p-8 md:p-10 rounded-[2.5rem] premium-shadow border border-outline-variant/15 relative overflow-hidden group">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black font-headline tracking-tight text-on-surface mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                        <p class="text-on-surface-variant text-lg font-medium">Your organizational sanctuary is optimized and ready for scaling.</p>
                        
                        <div class="mt-10 flex flex-wrap gap-4">
                            <button class="btn btn-primary rounded-xl font-bold uppercase tracking-widest text-xs h-auto py-4 px-8 border-none primary-gradient">Generate Report</button>
                            <button class="btn btn-ghost rounded-xl font-bold uppercase tracking-widest text-xs h-auto py-4 px-8 border-outline-variant/30 hover:bg-surface-container-low">View Logs</button>
                        </div>
                    </div>
                    <span class="material-symbols-outlined absolute -right-12 -bottom-12 text-[200px] opacity-5 text-primary">architecture</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="card bg-surface-container-low premium-shadow border border-outline-variant/10 group hover:bg-surface-bright transition-all rounded-[2rem]">
                        <div class="card-body p-8">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">badge</span>
                            </div>
                            <h4 class="text-xl font-bold font-headline text-on-surface mb-2">Employee Directory</h4>
                            <p class="text-sm text-on-surface-variant mb-6 font-medium">Manage your talent lifecycle from recruitment to offboarding.</p>
                            <div class="card-actions justify-end mt-auto">
                                <a href="#" class="btn btn-link btn-sm text-primary font-black uppercase tracking-widest no-underline hover:underline p-0">Open Directory</a>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-surface-container-low premium-shadow border border-outline-variant/10 group hover:bg-surface-bright transition-all rounded-[2rem]">
                        <div class="card-body p-8">
                            <div class="w-12 h-12 rounded-xl bg-tertiary/10 text-tertiary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">payments</span>
                            </div>
                            <h4 class="text-xl font-bold font-headline text-on-surface mb-2">Payroll Precision</h4>
                            <p class="text-sm text-on-surface-variant mb-6 font-medium">Process compensation with surgical accuracy and tax compliance.</p>
                            <div class="card-actions justify-end mt-auto">
                                <a href="#" class="btn btn-link btn-sm text-tertiary font-black uppercase tracking-widest no-underline hover:underline p-0">Run Payroll</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar / Widgets -->
            <div class="space-y-8">
                <!-- DaisyUI Menu Widget -->
                <div class="bg-surface-container-lowest p-8 rounded-[2.5rem] premium-shadow border border-outline-variant/15">
                    <h4 class="text-lg font-black font-headline text-on-surface mb-6 uppercase tracking-widest text-xs">Quick Access</h4>
                    <ul class="menu bg-surface-container-low rounded-2xl p-2 gap-1 font-medium">
                        <li><a class="py-3 hover:bg-primary/5 hover:text-primary active:bg-primary active:text-on-primary"><span class="material-symbols-outlined text-xl">person_add</span> Add Employee</a></li>
                        <li><a class="py-3 hover:bg-primary/5 hover:text-primary"><span class="material-symbols-outlined text-xl">event_note</span> Leave Requests <span class="badge badge-sm badge-error font-bold">12</span></a></li>
                        <li><a class="py-3 hover:bg-primary/5 hover:text-primary"><span class="material-symbols-outlined text-xl">description</span> Documents</a></li>
                        <li><a class="py-3 hover:bg-primary/5 hover:text-primary text-error"><span class="material-symbols-outlined text-xl">settings</span> Settings</a></li>
                    </ul>
                </div>

                <div class="bg-surface-container-lowest p-8 rounded-[2.5rem] premium-shadow border border-outline-variant/15">
                    <h4 class="text-lg font-black font-headline text-on-surface mb-6 uppercase tracking-widest text-xs">Recent Activity</h4>
                    <div class="space-y-6">
                        <div class="flex gap-4 group">
                            <div class="shrink-0 w-2 h-2 rounded-full bg-primary mt-2 animate-pulse"></div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">New Onboarding</p>
                                <p class="text-xs text-on-surface-variant font-medium">Sarah Chen joined Engineering.</p>
                                <p class="text-[10px] text-outline font-black uppercase mt-1">2 mins ago</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="shrink-0 w-2 h-2 rounded-full bg-tertiary mt-2"></div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">Payroll Complete</p>
                                <p class="text-xs text-on-surface-variant font-medium">March cycle disbursed successfully.</p>
                                <p class="text-[10px] text-outline font-black uppercase mt-1">Yesterday</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
