<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Subscription Plans</h2>
                <p class="text-sm text-slate-500 mt-1">Manage pricing, limits, and access for tenant subscriptions.</p>
            </div>
            <!-- Add a create button if needed in the future -->
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($plans as $plan)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm flex flex-col justify-between h-full group {{ !$plan->is_active ? 'opacity-70' : '' }}">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        @if($plan->price_monthly == 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                Free
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plan->is_active ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        @endif
                        <a href="{{ route('admin.plans.edit', $plan->slug) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $plan->name }}</h3>
                    <p class="text-3xl font-bold text-slate-900 mb-6">
                        ₹{{ number_format($plan->price_monthly, 0) }}<span class="text-sm font-medium text-slate-500">/mo</span>
                    </p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start gap-3 text-sm text-slate-600">
                            <span class="material-symbols-outlined text-indigo-500 text-lg shrink-0">check_circle</span>
                            <span>{{ $plan->max_employees == -1 ? 'Unlimited' : $plan->max_employees }} Employees</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm text-slate-600">
                            <span class="material-symbols-outlined text-indigo-500 text-lg shrink-0">check_circle</span>
                            <span>{{ $plan->max_modules == -1 ? 'Unlimited' : $plan->max_modules }} Modules</span>
                        </li>
                    </ul>
                </div>
                
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 rounded-b-xl">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">
                        Yearly: ₹{{ number_format($plan->price_yearly, 0) }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
