<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>{{ __('Dashboard Overview') }}</span>
            <div class="flex gap-2">
                <button class="btn btn-sm btn-ghost border-outline-variant/20 rounded-lg font-bold text-[10px] uppercase tracking-widest">
                    <span class="material-symbols-outlined text-sm">calendar_today</span> Mar 2026
                </button>
                <button class="btn btn-sm btn-primary primary-gradient rounded-lg font-bold text-[10px] uppercase tracking-widest border-none shadow-sm">
                    <span class="material-symbols-outlined text-sm">download</span> Export
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Compact Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/10 premium-shadow group hover:border-primary/30 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-on-surface-variant">Workforce</span>
                    <span class="material-symbols-outlined text-primary text-lg">groups</span>
                </div>
                <div class="text-2xl font-black font-headline">1,240</div>
                <div class="text-[9px] font-bold text-success mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[10px]">trending_up</span> 14%
                </div>
            </div>
            
            <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/10 premium-shadow group hover:border-secondary/30 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-on-surface-variant">Attendance</span>
                    <span class="material-symbols-outlined text-secondary text-lg">event_available</span>
                </div>
                <div class="text-2xl font-black font-headline">94.2%</div>
                <div class="text-[9px] font-bold text-success mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[10px]">trending_up</span> 2%
                </div>
            </div>

            <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/10 premium-shadow group hover:border-tertiary/30 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-on-surface-variant">Pending Leaves</span>
                    <span class="material-symbols-outlined text-tertiary text-lg">pending_actions</span>
                </div>
                <div class="text-2xl font-black font-headline text-tertiary">12</div>
                <div class="text-[9px] font-bold text-on-surface-variant mt-1 italic">Awaiting approval</div>
            </div>

            <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/10 premium-shadow group hover:border-primary/30 transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-on-surface-variant">Payroll Status</span>
                    <span class="material-symbols-outlined text-primary text-lg">payments</span>
                </div>
                <div class="text-2xl font-black font-headline">₹4.2M</div>
                <div class="badge badge-success badge-outline text-[8px] font-black uppercase h-4 py-0">Disbursed</div>
            </div>

            <div class="hidden lg:block bg-inverse-surface p-4 rounded-2xl premium-shadow primary-gradient relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="text-[10px] font-black uppercase tracking-[0.15em] text-white/70">System Health</span>
                    <div class="text-2xl font-black font-headline text-white">99.9%</div>
                    <div class="w-full bg-white/20 h-1 rounded-full mt-2">
                        <div class="bg-white h-full rounded-full" style="width: 99%"></div>
                    </div>
                </div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-6xl opacity-10 text-white">bolt</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Side: Main Tables and Charts (Dense) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Employee Overview Table -->
                <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/15 premium-shadow overflow-hidden">
                    <div class="p-5 border-b border-outline-variant/10 flex items-center justify-between">
                        <h3 class="font-black font-headline text-sm uppercase tracking-widest">Active Directory</h3>
                        <a href="#" class="text-[10px] font-black text-primary hover:underline uppercase tracking-tighter">Manage All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-xs table-zebra w-full">
                            <thead>
                                <tr class="text-on-surface-variant/70 border-b border-outline-variant/5">
                                    <th class="py-3 px-5">Name</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th class="text-right pr-5">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="font-medium text-[11px]">
                                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-primary/10 text-primary rounded-lg w-8 h-8 font-black text-[10px]">SC</div>
                                            </div>
                                            <div>
                                                <div class="font-black text-on-surface">Sarah Chen</div>
                                                <div class="text-[9px] opacity-60">sarah.c@company.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Engineering</td>
                                    <td>Lead Architect</td>
                                    <td><span class="badge badge-success badge-sm text-[8px] font-black text-white">ACTIVE</span></td>
                                    <td class="text-right pr-5">
                                        <button class="btn btn-ghost btn-xs rounded-md"><span class="material-symbols-outlined text-sm">edit</span></button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-secondary/10 text-secondary rounded-lg w-8 h-8 font-black text-[10px]">MV</div>
                                            </div>
                                            <div>
                                                <div class="font-black text-on-surface">Marcus Vance</div>
                                                <div class="text-[9px] opacity-60">m.vance@company.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Operations</td>
                                    <td>Manager</td>
                                    <td><span class="badge badge-warning badge-sm text-[8px] font-black text-white">ON LEAVE</span></td>
                                    <td class="text-right pr-5">
                                        <button class="btn btn-ghost btn-xs rounded-md"><span class="material-symbols-outlined text-sm">edit</span></button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="avatar placeholder">
                                                <div class="bg-tertiary/10 text-tertiary rounded-lg w-8 h-8 font-black text-[10px]">JR</div>
                                            </div>
                                            <div>
                                                <div class="font-black text-on-surface">Julian Rodriguez</div>
                                                <div class="text-[9px] opacity-60">j.rod@company.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Marketing</td>
                                    <td>Sr. Designer</td>
                                    <td><span class="badge badge-success badge-sm text-[8px] font-black text-white">ACTIVE</span></td>
                                    <td class="text-right pr-5">
                                        <button class="btn btn-ghost btn-xs rounded-md"><span class="material-symbols-outlined text-sm">edit</span></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-surface-container-low p-6 rounded-[2rem] border border-outline-variant/10 premium-shadow">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] mb-6 text-on-surface-variant">Department Distribution</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-[10px] font-black mb-1"><span>ENGINEERING</span><span>45%</span></div>
                                <progress class="progress progress-primary h-1.5" value="45" max="100"></progress>
                            </div>
                            <div>
                                <div class="flex justify-between text-[10px] font-black mb-1"><span>OPERATIONS</span><span>25%</span></div>
                                <progress class="progress progress-secondary h-1.5" value="25" max="100"></progress>
                            </div>
                            <div>
                                <div class="flex justify-between text-[10px] font-black mb-1"><span>SALES</span><span>30%</span></div>
                                <progress class="progress progress-tertiary h-1.5" value="30" max="100"></progress>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest p-6 rounded-[2rem] border border-outline-variant/10 premium-shadow flex flex-col justify-center items-center text-center">
                        <div class="radial-progress text-primary mb-4" style="--value:82; --size:5rem; --thickness: 4px;" role="progressbar">
                            <span class="text-[10px] font-black">82%</span>
                        </div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-on-surface">Target Met</h4>
                        <p class="text-[9px] text-on-surface-variant font-medium mt-1">Quarterly productivity alignment</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Activity and Tasks (Dense Sidebar) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Task List -->
                <div class="bg-surface-container-lowest p-6 rounded-[2rem] border border-outline-variant/15 premium-shadow">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] mb-6 text-on-surface-variant">Critical Tasks</h4>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl group hover:bg-white transition-all cursor-pointer">
                            <input type="checkbox" class="checkbox checkbox-xs checkbox-primary rounded-md" />
                            <span class="text-[11px] font-black text-on-surface line-through decoration-primary/30">Verify payroll logs</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl group hover:bg-white transition-all cursor-pointer">
                            <input type="checkbox" class="checkbox checkbox-xs checkbox-primary rounded-md" />
                            <span class="text-[11px] font-black text-on-surface">Approve Sarah's Leave</span>
                            <span class="badge badge-xs badge-error text-white font-black text-[7px] ml-auto">URGENT</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl group hover:bg-white transition-all cursor-pointer">
                            <input type="checkbox" class="checkbox checkbox-xs checkbox-primary rounded-md" />
                            <span class="text-[11px] font-black text-on-surface">Compliance audit</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Feed -->
                <div class="bg-surface-container-lowest p-6 rounded-[2rem] border border-outline-variant/15 premium-shadow">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.2em] mb-6 text-on-surface-variant">Live Feed</h4>
                    <div class="space-y-6 relative before:absolute before:left-[7px] before:top-2 before:bottom-2 before:w-[1px] before:bg-outline-variant/20">
                        @forelse($recentActivities as $activity)
                        <div class="flex gap-4 relative">
                            @php
                                $dotColor = 'bg-primary';
                                if($activity['type'] === 'deleted') $dotColor = 'bg-error';
                                if($activity['type'] === 'updated') $dotColor = 'bg-secondary';
                            @endphp
                            <div class="w-4 h-4 rounded-full {{ $dotColor }} border-4 border-white premium-shadow z-10 mt-1"></div>
                            <div>
                                <p class="text-[11px] font-black text-on-surface capitalize">{{ $activity['description'] }}</p>
                                <p class="text-[10px] text-on-surface-variant font-medium">{{ $activity['subject_name'] }} by {{ $activity['causer_name'] }}</p>
                                <p class="text-[8px] text-outline font-black uppercase mt-1">{{ $activity['time_ago'] }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-[10px] text-center text-on-surface-variant py-10 italic">No recent activity curated.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
