<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Super Admin Dashboard</h2>
                <p class="text-sm text-slate-500 mt-1">Overview and system health metrics</p>
            </div>
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
                <span class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 tracking-wide uppercase">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    System Operational
                </span>
            </div>
        </div>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tenants -->
        <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Total Tenants</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ \App\Models\Tenant::count() }}</p>
                </div>
                <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Tenants -->
        <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Active Tenants</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ \App\Models\Tenant::where('status', 'active')->count() }}</p>
                </div>
                <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Admin Users -->
        <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Admin Users</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ \App\Models\User::role(\App\Core\Constants\RoleConstants::SADMIN)->count() ?? 1 }}</p>
                </div>
                <div class="p-3 rounded-xl bg-sky-50 text-sky-600 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">System Load</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">12%</p>
                </div>
                <div class="p-3 rounded-xl bg-amber-50 text-amber-600 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Quick Actions Card -->
        <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-900 tracking-tight mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.tenants.create') }}" class="flex items-center p-3 bg-slate-50 text-slate-700 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 rounded-lg transition-colors border border-slate-200">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 mr-4">
                        <span class="material-symbols-outlined text-sm">add_business</span>
                    </div>
                    <span class="font-semibold text-sm">Create New Tenant</span>
                </a>

                <a href="#" class="flex items-center p-3 bg-slate-50 text-slate-700 hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 rounded-lg transition-colors border border-slate-200">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 mr-4">
                        <span class="material-symbols-outlined text-sm">person_add</span>
                    </div>
                    <span class="font-semibold text-sm">Add Admin User</span>
                </a>

                <a href="#" class="flex items-center p-3 bg-slate-50 text-slate-700 hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200 rounded-lg transition-colors border border-slate-200">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 mr-4">
                        <span class="material-symbols-outlined text-sm">settings</span>
                    </div>
                    <span class="font-semibold text-sm">System Settings</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-900 tracking-tight mb-4">Recent Activity</h3>
            <div class="space-y-5 mt-2">
                <div class="flex items-start">
                    <div class="w-2.5 h-2.5 mt-1.5 bg-emerald-500 rounded-full mr-4 shadow-sm shadow-emerald-500/30"></div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-900">System started successfully</p>
                        <p class="text-xs text-slate-500 mt-0.5">2 minutes ago</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-2.5 h-2.5 mt-1.5 bg-indigo-500 rounded-full mr-4 shadow-sm shadow-indigo-500/30"></div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-900">New tenant created</p>
                        <p class="text-xs text-slate-500 mt-0.5">1 hour ago</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-2.5 h-2.5 mt-1.5 bg-amber-500 rounded-full mr-4 shadow-sm shadow-amber-500/30"></div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-900">Admin user logged in</p>
                        <p class="text-xs text-slate-500 mt-0.5">3 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tenants -->
    <div class="card bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Recent Tenants</h3>
            <a href="{{ route('admin.tenants.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">View All</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 bg-slate-50 border-b border-slate-200 uppercase">
                    <tr>
                        <th class="px-6 py-3 font-bold tracking-wider">Name</th>
                        <th class="px-6 py-3 font-bold tracking-wider hidden sm:table-cell">Domain</th>
                        <th class="px-6 py-3 font-bold tracking-wider hidden md:table-cell">Type</th>
                        <th class="px-6 py-3 font-bold tracking-wider">Status</th>
                        <th class="px-6 py-3 font-bold tracking-wider hidden lg:table-cell">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse(\App\Models\Tenant::latest()->take(5)->get() as $tenant)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $tenant->name ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-500 sm:hidden">{{ $tenant->slug ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-mono text-xs hidden sm:table-cell">
                            {{ $tenant->slug ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium hidden md:table-cell">
                            {{ ucfirst($tenant->plan_id ?? 'School') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($tenant->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    {{ ucfirst($tenant->status) ?? 'Inactive' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs font-medium hidden lg:table-cell">
                            {{ $tenant->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">business</span>
                                <p class="text-sm font-medium">No tenants found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
