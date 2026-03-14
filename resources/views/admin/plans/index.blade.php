<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Subscription Plans</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Manage pricing, limits, and access for tenant subscriptions.</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($plans as $plan)
            <div class="bg-surface-container-lowest p-8 rounded-[2.5rem] border border-outline-variant/15 premium-shadow flex flex-col justify-between h-full group {{ !$plan->is_active ? 'opacity-60' : '' }}">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="badge {{ $plan->price_monthly == 0 ? 'badge-ghost' : 'badge-primary' }} text-[9px] font-black uppercase tracking-widest px-2 py-1">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <a href="{{ route('admin.plans.edit', $plan->id) }}" class="btn btn-ghost btn-xs btn-square rounded-lg hover:bg-secondary/10 hover:text-secondary">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                    </div>
                    <h3 class="text-2xl font-black font-headline text-on-surface mb-1">{{ $plan->name }}</h3>
                    <p class="text-3xl font-extrabold text-on-surface mb-6">
                        ₹{{ number_format($plan->price_monthly, 0) }}<span class="text-sm font-medium text-on-surface-variant">/mo</span>
                    </p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center gap-3 text-sm font-medium text-on-surface">
                            <span class="material-symbols-outlined text-tertiary text-lg">check_circle</span>
                            {{ $plan->max_employees == -1 ? 'Unlimited' : $plan->max_employees }} Employees
                        </li>
                        <li class="flex items-center gap-3 text-sm font-medium text-on-surface">
                            <span class="material-symbols-outlined text-tertiary text-lg">check_circle</span>
                            {{ $plan->max_modules == -1 ? 'Unlimited' : $plan->max_modules }} Modules
                        </li>
                    </ul>
                </div>
                
                <div class="pt-6 border-t border-outline-variant/10">
                    <p class="text-[10px] text-on-surface-variant font-bold uppercase tracking-widest">Yearly: ₹{{ number_format($plan->price_yearly, 0) }}</p>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
