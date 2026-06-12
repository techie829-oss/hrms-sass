<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                    {{ $tenant->name }}
                    @if($tenant->status === 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider">Active</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wider">Suspended</span>
                    @endif
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Manage tenant subscription, modules, users and activity.
                </p>
            </div>
            <a href="{{ route('admin.tenants.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-xl font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm text-sm">
                <span class="material-symbols-outlined text-lg">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== LEFT COLUMN: Info + Subscription ===== --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Basic Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2 mb-5">
                    <span class="material-symbols-outlined text-indigo-600">business</span>
                    Tenant Overview
                </h3>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <div class="text-slate-500 text-xs font-medium uppercase tracking-wider mb-1">Tenant ID</div>
                        <div class="font-mono bg-slate-50 px-3 py-2 rounded-lg text-slate-700 text-xs border border-slate-200 break-all">{{ $tenant->id }}</div>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs font-medium uppercase tracking-wider mb-1">Domain</div>
                        <a href="http://{{ $tenant->domains->first()?->domain }}" target="_blank"
                           class="inline-flex items-center gap-1 font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                            {{ $tenant->domains->first()?->domain }}
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </a>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs font-medium uppercase tracking-wider mb-1">Database Mode</div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium {{ $tenant->mode === 'dedicated' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }} uppercase tracking-wider">
                            <span class="material-symbols-outlined text-sm mr-1.5">{{ $tenant->mode === 'dedicated' ? 'storage' : 'share' }}</span>
                            {{ $tenant->mode }}
                        </span>
                    </div>
                    <div>
                        <div class="text-slate-500 text-xs font-medium uppercase tracking-wider mb-1">Contact</div>
                        <div class="font-medium text-slate-800">{{ $tenant->email }}</div>
                        <div class="text-slate-500 mt-0.5">{{ $tenant->contact_no ?? '—' }}</div>
                    </div>

                    <hr class="border-slate-100 my-4">

                    <div class="space-y-2">
                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-white border border-slate-300 rounded-xl font-medium text-slate-700 hover:bg-slate-50 transition-colors shadow-sm text-sm">
                            <span class="material-symbols-outlined text-lg">edit</span> Edit Details
                        </a>
                        <form action="{{ route('admin.tenants.toggle-status', $tenant) }}" method="POST">
                            @csrf @method('PATCH')
                            @if($tenant->status === 'active')
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl font-medium hover:bg-amber-100 transition-colors text-sm"
                                    onclick="return confirm('Suspend this tenant? They will lose access.')">
                                    <span class="material-symbols-outlined text-lg">pause_circle</span> Suspend Tenant
                                </button>
                            @else
                                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-emerald-600 text-white rounded-xl font-medium hover:bg-emerald-700 transition-colors shadow-sm text-sm">
                                    <span class="material-symbols-outlined text-lg">play_circle</span> Reactivate Tenant
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Subscription --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2 mb-5">
                    <span class="material-symbols-outlined text-indigo-600">credit_card</span>
                    Subscription Plan
                </h3>
                <form action="{{ route('admin.tenants.update-plan', $tenant) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Active Plan</label>
                        <select name="plan_id" class="w-full rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 text-slate-700">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->slug }}"
                                    {{ $tenant->plan_id === $plan->slug ? 'selected' : '' }}>
                                    {{ $plan->name }}
                                    ({{ $plan->max_modules == -1 ? 'Unlimited' : $plan->max_modules }} Modules,
                                    ₹{{ number_format($plan->price_monthly) }}/mo)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-xl font-medium hover:bg-indigo-100 transition-colors text-sm">
                        <span class="material-symbols-outlined text-lg">sync</span> Update Plan
                    </button>
                </form>

                <hr class="border-slate-100 my-5">

                {{-- Razorpay Activation --}}
                @php
                    $subscription = $tenant->subscription;
                    $planMismatch = $subscription && $subscription->plan_id !== $tenant->plan_id;
                    $activePlan = $plans->where('slug', $tenant->plan_id)->first();
                @endphp

                @if(!$subscription || $subscription->status !== 'active' || $planMismatch)
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 text-center">
                        <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-2">Action Required</p>
                        <h4 class="text-sm font-bold text-slate-800 mb-1">
                            @if($planMismatch)
                                Plan Mismatch Detected
                            @else
                                Subscription Inactive
                            @endif
                        </h4>
                        <p class="text-xs text-slate-500 font-medium mb-4 leading-relaxed">
                            To activate the <strong>{{ $activePlan->name ?? 'selected' }}</strong> features, please complete the payment.
                        </p>
                        <a href="{{ route('admin.tenants.checkout', [$tenant->id, $activePlan->slug ?? 'basic']) }}" 
                           class="inline-flex items-center justify-center gap-2 w-full px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-medium text-white hover:bg-indigo-700 transition-colors shadow-sm text-sm">
                            <span class="material-symbols-outlined text-lg">payments</span>
                            Pay & Activate (₹{{ number_format($activePlan->price_monthly ?? 0) }})
                        </a>
                    </div>
                @else
                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Active Subscription</span>
                            <span class="material-symbols-outlined text-emerald-600 text-lg">verified</span>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Payment ID:</span>
                                <span class="font-mono font-medium text-slate-800 bg-white px-2 py-0.5 rounded border border-slate-200">{{ substr($subscription->razorpay_payment_id, -8) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Expires On:</span>
                                <span class="font-medium text-slate-800">{{ $subscription->ends_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- ===== RIGHT COLUMN: Modules + Users + Logs ===== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Module Control --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-indigo-600">extension</span>
                            Module Control Center
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">Enable or disable HRMS features for this tenant.</p>
                    </div>
                    @php
                        $activePlan = $plans->where('slug', $tenant->plan_id)->first();
                        $usedCount  = count($enabledModules);
                        $maxCount   = $activePlan?->max_modules ?? -1;
                        $atLimit    = $maxCount !== -1 && $usedCount >= $maxCount;
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium {{ $atLimit ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                        {{ $usedCount }} / {{ $maxCount === -1 ? '∞' : $maxCount }} Modules
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($availableModules as $module)
                        @php $isEnabled = in_array($module->id, $enabledModules); @endphp
                        <div class="border rounded-xl p-4 flex items-center justify-between transition-all duration-200
                                    {{ $isEnabled ? 'bg-indigo-50/30 border-indigo-200 hover:border-indigo-300' : 'bg-slate-50/50 border-slate-200 hover:border-slate-300' }}">
                            <div class="pr-4">
                                <div class="font-semibold text-slate-800 flex items-center flex-wrap gap-2">
                                    {{ $module->name }}
                                    @if($isEnabled)
                                        <span class="inline-flex items-center gap-1 text-emerald-600 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 px-1.5 py-0.5 rounded-sm border border-emerald-100">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Enabled
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $module->description }}</div>
                                <div class="flex items-center gap-2 mt-2.5">
                                    <div class="font-mono text-[10px] text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200">{{ $module->slug }}</div>
                                    @if($module->is_free)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">Free</span>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('admin.tenants.toggle-module', $tenant) }}" method="POST" class="shrink-0">
                                @csrf @method('PATCH')
                                <input type="hidden" name="module_slug" value="{{ $module->slug }}">
                                @if($isEnabled)
                                    <input type="hidden" name="action" value="disable">
                                    <button class="inline-flex items-center justify-center p-2 rounded-lg text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors border border-transparent hover:border-rose-200" title="Disable {{ $module->name }}" onclick="return confirm('Disable {{ $module->name }}?')">
                                        <span class="material-symbols-outlined text-2xl">toggle_on</span>
                                    </button>
                                @else
                                    <input type="hidden" name="action" value="enable">
                                    <button class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors border border-transparent hover:border-emerald-200" title="Enable {{ $module->name }}">
                                        <span class="material-symbols-outlined text-2xl">toggle_off</span>
                                    </button>
                                @endif
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Global Users --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600">group</span>
                        Tenant Users
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $tenantUsers->count() }} Users
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($tenantUsers as $user)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3">
                                            <span class="material-symbols-outlined text-2xl text-slate-400">person_off</span>
                                        </div>
                                        <h4 class="text-sm font-medium text-slate-900">No users found</h4>
                                        <p class="mt-1 text-sm text-slate-500">This tenant does not have any active users yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Activity Log --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600">history</span>
                        Recent Activity (Last 15)
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Time</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-40">User</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Description</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($activities as $activity)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800">
                                        {{ $activity->causer ? $activity->causer->name : 'System' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $activity->description }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3">
                                            <span class="material-symbols-outlined text-2xl text-slate-400">history_toggle_off</span>
                                        </div>
                                        <h4 class="text-sm font-medium text-slate-900">No activity</h4>
                                        <p class="mt-1 text-sm text-slate-500">No recent activity has been logged for this tenant.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
