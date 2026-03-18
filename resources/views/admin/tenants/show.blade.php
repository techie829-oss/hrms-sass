<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface flex items-center gap-3">
                    {{ $tenant->name }}
                    @if($tenant->status === 'active')
                        <span class="badge badge-success text-white text-[10px] font-bold uppercase tracking-widest px-3">Active</span>
                    @else
                        <span class="badge badge-error text-white text-[10px] font-bold uppercase tracking-widest px-3">Suspended</span>
                    @endif
                </h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">
                    Manage tenant subscription, modules, users and activity.
                </p>
            </div>
            <a href="{{ route('admin.tenants.index') }}" class="btn btn-ghost gap-2">
                <span class="material-symbols-outlined text-xl">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm mb-6">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error shadow-sm mb-6">
            <span class="material-symbols-outlined">warning</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ===== LEFT COLUMN: Info + Subscription ===== --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Basic Info --}}
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">business</span>
                        Tenant Overview
                    </h2>
                    <div class="space-y-4 text-sm">
                        <div>
                            <div class="text-base-content/60 mb-1">Tenant ID</div>
                            <div class="font-mono bg-base-200 px-3 py-2 rounded-lg text-xs">{{ $tenant->id }}</div>
                        </div>
                        <div>
                            <div class="text-base-content/60 mb-1">Domain</div>
                            <a href="http://{{ $tenant->domains->first()?->domain }}" target="_blank"
                               class="link link-primary flex items-center gap-1 font-medium">
                                {{ $tenant->domains->first()?->domain }}
                                <span class="material-symbols-outlined text-base">open_in_new</span>
                            </a>
                        </div>
                        <div>
                            <div class="text-base-content/60 mb-1">Database Mode</div>
                            <span class="badge {{ $tenant->mode === 'dedicated' ? 'badge-primary' : 'badge-neutral' }} font-bold uppercase text-[10px] tracking-widest">
                                <span class="material-symbols-outlined text-base mr-1">{{ $tenant->mode === 'dedicated' ? 'storage' : 'share' }}</span>
                                {{ $tenant->mode }}
                            </span>
                        </div>
                        <div>
                            <div class="text-base-content/60 mb-1">Contact</div>
                            <div class="font-medium">{{ $tenant->email }}</div>
                            <div class="text-base-content/70">{{ $tenant->contact_no ?? '—' }}</div>
                        </div>
                        <div class="divider my-1"></div>
                        <div>
                            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-outline btn-sm w-full gap-2 mb-2">
                                <span class="material-symbols-outlined text-base">edit</span> Edit Details
                            </a>
                            <form action="{{ route('admin.tenants.toggle-status', $tenant) }}" method="POST">
                                @csrf @method('PATCH')
                                @if($tenant->status === 'active')
                                    <button type="submit" class="btn btn-warning btn-sm w-full gap-2"
                                        onclick="return confirm('Suspend this tenant? They will lose access.')">
                                        <span class="material-symbols-outlined text-base">pause_circle</span> Suspend Tenant
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-success btn-sm w-full gap-2 text-white">
                                        <span class="material-symbols-outlined text-base">play_circle</span> Reactivate Tenant
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subscription --}}
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">credit_card</span>
                        Subscription Plan
                    </h2>
                    <form action="{{ route('admin.tenants.update-plan', $tenant) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="form-control w-full mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Active Plan</span>
                            </label>
                            <select name="plan_id" class="select select-bordered w-full">
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
                        <button type="submit" class="btn btn-primary w-full gap-2">
                            <span class="material-symbols-outlined text-base">sync</span> Update Plan Selection
                        </button>
                    </form>

                    <div class="divider my-1"></div>

                    {{-- Razorpay Activation --}}
                    @php
                        $subscription = $tenant->subscription;
                        $planMismatch = $subscription && $subscription->plan_id !== $tenant->plan_id;
                        $activePlan = $plans->where('slug', $tenant->plan_id)->first();
                    @endphp

                    @if(!$subscription || $subscription->status !== 'active' || $planMismatch)
                        <div class="bg-primary/5 border border-primary/10 rounded-xl p-4 text-center">
                            <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-3">Action Required</p>
                            <h4 class="text-sm font-bold text-on-surface mb-1">
                                @if($planMismatch)
                                    Plan Mismatch Detected
                                @else
                                    Subscription Inactive
                                @endif
                            </h4>
                            <p class="text-[10px] text-on-surface-variant font-medium mb-4 leading-relaxed">
                                To activate the <strong>{{ $activePlan->name }}</strong> features, please complete the payment.
                            </p>
                            <a href="{{ route('admin.tenants.checkout', [$tenant->id, $activePlan->slug]) }}" 
                               class="btn btn-primary btn-sm w-full gap-2 shadow-sm shadow-primary/30">
                                <span class="material-symbols-outlined text-sm">payments</span>
                                Pay & Activate (₹{{ number_format($activePlan->price_monthly) }})
                            </a>
                        </div>
                    @else
                        <div class="bg-success/5 border border-success/10 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[9px] font-bold text-success uppercase tracking-widest">Active Subscription</span>
                                <span class="material-symbols-outlined text-success text-sm">verified</span>
                            </div>
                            <div class="space-y-1.5 text-[11px]">
                                <div class="flex justify-between">
                                    <span class="opacity-60">Payment ID:</span>
                                    <span class="font-mono font-bold">{{ substr($subscription->razorpay_payment_id, -8) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="opacity-60">Expires On:</span>
                                    <span class="font-bold">{{ $subscription->ends_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ===== RIGHT COLUMN: Modules + Users + Logs ===== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Module Control --}}
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="card-title text-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">extension</span>
                                Module Control Center
                            </h2>
                            <p class="text-sm text-base-content/70 mt-0.5">Enable or disable HRMS features for this tenant.</p>
                        </div>
                        @php
                            $activePlan = $plans->where('slug', $tenant->plan_id)->first();
                            $usedCount  = count($enabledModules);
                            $maxCount   = $activePlan?->max_modules ?? -1;
                            $atLimit    = $maxCount !== -1 && $usedCount >= $maxCount;
                        @endphp
                        <span class="badge {{ $atLimit ? 'badge-error' : 'badge-success' }} text-white font-bold">
                            {{ $usedCount }} / {{ $maxCount === -1 ? '∞' : $maxCount }} Modules
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($availableModules as $module)
                            @php $isEnabled = in_array($module->id, $enabledModules); @endphp
                            <div class="border border-base-200 rounded-xl p-4 flex items-center justify-between
                                        {{ $isEnabled ? 'bg-success/5 border-success/20' : 'bg-base-50' }}
                                        hover:border-primary/30 transition-colors">
                                <div>
                                    <div class="font-semibold">{{ $module->name }} @if($isEnabled)<span class="text-success text-[10px] ml-1">● Enabled</span>@endif</div>
                                    <div class="text-[10px] text-base-content/60 mt-1 max-w-[200px] leading-tight">{{ $module->description }}</div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="font-mono text-[9px] text-base-content/40">{{ $module->slug }}</div>
                                        @if($module->is_free)
                                            <div class="badge badge-ghost badge-xs text-[9px]">Free</div>
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('admin.tenants.toggle-module', $tenant) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="module_slug" value="{{ $module->slug }}">
                                    @if($isEnabled)
                                        <input type="hidden" name="action" value="disable">
                                        <button class="btn btn-sm btn-outline btn-error"
                                            onclick="return confirm('Disable {{ $module->name }}?')">
                                            <span class="material-symbols-outlined text-base">toggle_on</span> On
                                        </button>
                                    @else
                                        <input type="hidden" name="action" value="enable">
                                        <button class="btn btn-sm btn-outline btn-success">
                                            <span class="material-symbols-outlined text-base">toggle_off</span> Off
                                        </button>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Global Users --}}
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="card-title text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">group</span>
                            Tenant Users
                        </h2>
                        <span class="badge badge-neutral font-bold">{{ $tenantUsers->count() }} users</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-sm w-full">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tenantUsers as $user)
                                    <tr>
                                        <td class="font-medium">{{ $user->name }}</td>
                                        <td class="text-sm text-base-content/70">{{ $user->email }}</td>
                                        <td class="text-xs text-base-content/60">{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-base-content/50 py-6">
                                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-30">person_off</span>
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Activity Log --}}
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body">
                    <h2 class="card-title text-lg mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">history</span>
                        Recent Activity (Last 15)
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="table table-sm w-full">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                    <tr>
                                        <td class="whitespace-nowrap text-xs text-base-content/60">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </td>
                                        <td class="text-xs font-medium">
                                            {{ $activity->causer ? $activity->causer->name : 'System' }}
                                        </td>
                                        <td class="text-sm">{{ $activity->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-8">
                                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-30">history_toggle_off</span>
                                            <span class="text-base-content/50 text-sm">No activity logged yet.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
