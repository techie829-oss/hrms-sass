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
                <div class="bg-surface-container-lowest p-8 rounded-[2.5rem] premium-shadow border border-outline-variant/15">
                    <h4 class="text-lg font-black font-headline text-on-surface mb-6 uppercase tracking-widest text-xs">System Control</h4>
                    <ul class="menu bg-surface-container-low rounded-2xl p-2 gap-1 font-medium">
                        <li><a href="{{ route('admin.tenants.create') }}" class="py-3 hover:bg-primary/5 hover:text-primary"><span class="material-symbols-outlined text-xl">add_business</span> Create Tenant</a></li>
                        <li><a href="{{ route('admin.modules.index') }}" class="py-3 hover:bg-primary/5 hover:text-primary"><span class="material-symbols-outlined text-xl">widgets</span> Manage Modules</a></li>
                        <li><a href="{{ route('admin.plans.index') }}" class="py-3 hover:bg-primary/5 hover:text-primary"><span class="material-symbols-outlined text-xl">security</span> Manage Plans</a></li>
                        <li><a class="py-3 hover:bg-primary/5 hover:text-primary text-error"><span class="material-symbols-outlined text-xl">report_problem</span> Critical Alerts <span class="badge badge-error text-white font-black text-[10px] animate-pulse">0</span></a></li>
                    </ul>
                </div>

                <div class="bg-inverse-surface p-8 rounded-[2.5rem] premium-shadow text-on-primary primary-gradient relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-lg font-black font-headline mb-4 uppercase tracking-widest text-xs">Infrastructure</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold opacity-70">CPU Usage</span>
                                <span class="font-black">12%</span>
                            </div>
                            <progress class="progress progress-primary w-full h-2 bg-white/20" value="12" max="100"></progress>
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold opacity-70">Memory</span>
                                <span class="font-black">2.1GB / 8GB</span>
                            </div>
                            <progress class="progress progress-secondary w-full h-2 bg-white/20" value="26" max="100"></progress>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
