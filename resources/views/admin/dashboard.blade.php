<x-app-layout>
    <x-slot name="header">
        {{ __('Super Admin Dashboard') }}
    </x-slot>

    @php
        $tenantCount = \App\Models\Tenant::count();
        $recentTenants = \App\Models\Tenant::latest()->take(5)->get();
    @endphp

    <div class="space-y-10">
        <!-- DaisyUI Stats for Admin -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="stats shadow-xl bg-surface-container-lowest border border-outline-variant/15 rounded-[2rem] overflow-hidden md:col-span-4">
                <div class="stat p-8">
                    <div class="stat-figure text-primary">
                        <span class="material-symbols-outlined text-4xl">domain</span>
                    </div>
                    <div class="stat-title font-bold uppercase tracking-widest text-[10px] text-on-surface-variant">Active Tenants</div>
                    <div class="stat-value text-primary font-headline text-4xl">{{ $tenantCount }}</div>
                    <div class="stat-desc font-medium mt-1">Total provisioned units</div>
                </div>
                
                <div class="stat p-8">
                    <div class="stat-figure text-secondary">
                        <span class="material-symbols-outlined text-4xl">payments</span>
                    </div>
                    <div class="stat-title font-bold uppercase tracking-widest text-[10px] text-on-surface-variant">System Revenue</div>
                    <div class="stat-value text-secondary font-headline text-4xl">₹0.00</div>
                    <div class="stat-desc font-medium mt-1">Stripe integration pending</div>
                </div>
                
                <div class="stat p-8">
                    <div class="stat-figure text-tertiary">
                        <span class="material-symbols-outlined text-4xl">monitoring</span>
                    </div>
                    <div class="stat-title font-bold uppercase tracking-widest text-[10px] text-on-surface-variant">System Health</div>
                    <div class="stat-value text-tertiary font-headline text-4xl">99.9%</div>
                    <div class="stat-desc font-medium mt-1">All systems operational</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Tenant Management Area -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-surface-container-lowest p-8 md:p-10 rounded-[2.5rem] premium-shadow border border-outline-variant/15">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-2xl font-black font-headline tracking-tight text-on-surface">Recent Subscriptions</h3>
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-ghost rounded-xl font-bold uppercase tracking-widest text-[10px]">View All Tenants</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-on-surface-variant font-label border-b border-outline-variant/10">
                                    <th class="bg-transparent uppercase tracking-widest text-[10px]">Company</th>
                                    <th class="bg-transparent uppercase tracking-widest text-[10px]">Subdomain</th>
                                    <th class="bg-transparent uppercase tracking-widest text-[10px]">Plan</th>
                                    <th class="bg-transparent uppercase tracking-widest text-[10px]">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-on-surface font-medium">
                                @forelse($recentTenants as $tenant)
                                <tr class="border-b border-outline-variant/5">
                                    <td>{{ $tenant->name }}</td>
                                    <td><span class="badge badge-ghost font-mono text-[10px]">{{ $tenant->slug }}</span></td>
                                    <td><span class="badge badge-primary badge-outline text-[10px] font-bold uppercase">{{ $tenant->plan_id }}</span></td>
                                    <td><span class="badge {{ $tenant->status === 'active' ? 'badge-success' : 'badge-error' }} text-[10px] text-white font-bold uppercase">{{ $tenant->status }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 opacity-50">No tenants found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Admin Quick Actions -->
            <div class="space-y-8">
                <div class="bg-surface-container-lowest p-8 md:p-10 rounded-[2.5rem] premium-shadow border border-outline-variant/15 h-full">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-2 h-6 bg-primary rounded-full"></div>
                        <h4 class="text-xs font-black font-headline text-outline uppercase tracking-[0.2em] leading-none">System Control</h4>
                    </div>
                    
                    <ul class="flex flex-col gap-3 font-medium">
                        <li>
                            <a href="{{ route('admin.tenants.create') }}" class="group flex items-center gap-4 p-4 rounded-2xl bg-surface-container-low hover:bg-primary/5 hover:text-primary transition-all">
                                <span class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-xl">add_business</span>
                                </span>
                                <span class="text-sm font-bold font-headline">Create Tenant</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.modules.index') }}" class="group flex items-center gap-4 p-4 rounded-2xl bg-surface-container-low hover:bg-primary/5 hover:text-primary transition-all">
                                <span class="w-10 h-10 rounded-xl bg-tertiary/10 text-tertiary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-xl">widgets</span>
                                </span>
                                <span class="text-sm font-bold font-headline">Manage Modules</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.plans.index') }}" class="group flex items-center gap-4 p-4 rounded-2xl bg-surface-container-low hover:bg-primary/5 hover:text-primary transition-all">
                                <span class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-xl">security</span>
                                </span>
                                <span class="text-sm font-bold font-headline">Security Audit</span>
                            </a>
                        </li>
                        <li>
                            <a class="group flex items-center gap-4 p-4 rounded-2xl bg-surface-container-low hover:bg-error/5 hover:text-error transition-all cursor-pointer">
                                <span class="w-10 h-10 rounded-xl bg-error/10 text-error flex items-center justify-center group-hover:scale-110 transition-transform relative">
                                    <span class="material-symbols-outlined text-xl">report_problem</span>
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-error text-white text-[9px] font-black rounded-full flex items-center justify-center animate-pulse">0</span>
                                </span>
                                <span class="text-sm font-bold font-headline">Critical Alerts</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="bg-inverse-surface p-8 rounded-[2.5rem] premium-shadow text-on-primary primary-gradient relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-lg font-black font-headline mb-4 uppercase tracking-widest text-xs">Infrastructure</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold opacity-70 uppercase tracking-tighter">CPU Usage</span>
                                <span class="font-black">12%</span>
                            </div>
                            <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-primary shadow-[0_0_10px_rgba(var(--primary-rgb),0.5)]" style="width: 12%"></div>
                            </div>
                            
                            <div class="flex justify-between items-center text-xs pt-2">
                                <span class="font-bold opacity-70 uppercase tracking-tighter">Memory</span>
                                <span class="font-black">2.1GB / 8GB</span>
                            </div>
                            <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-tertiary shadow-[0_0_10px_rgba(var(--tertiary-rgb),0.5)]" style="width: 26%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
