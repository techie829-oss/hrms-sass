<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Leads & Demo Requests</h2>
                <p class="text-sm text-slate-500 mt-1">All incoming leads — from the landing page and tenant CRM</p>
            </div>
        </div>
    </x-slot>

    {{-- ══════════════════════════════════════════════════════
         SECTION 1: Landing Page Demo Requests (demo_leads)
    ══════════════════════════════════════════════════════ --}}
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-indigo-600 text-2xl">contact_mail</span>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Website Demo Requests</h3>
                <p class="text-xs text-slate-500">People who filled the "Start Free Demo" form on the landing page</p>
            </div>
            <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                {{ $demoLeads->total() }} total
            </span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 bg-slate-50 border-b border-slate-200 uppercase">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wider">Name & Email</th>
                            <th class="px-6 py-4 font-bold tracking-wider hidden sm:table-cell">Mobile</th>
                            <th class="px-6 py-4 font-bold tracking-wider hidden md:table-cell">Company</th>
                            <th class="px-6 py-4 font-bold tracking-wider hidden lg:table-cell">Country</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($demoLeads as $lead)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $lead->name }}</div>
                                <div class="text-xs text-slate-500">{{ $lead->email }}</div>
                                <div class="text-xs text-slate-400 sm:hidden mt-0.5">{{ $lead->mobile }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium hidden sm:table-cell">
                                {{ $lead->mobile }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 hidden md:table-cell">
                                {{ $lead->company_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $lead->country ?? '-' }}
                                </span>
                                <div class="text-xs text-slate-400 mt-1">{{ $lead->ip_address ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                <div class="font-semibold">{{ $lead->created_at->format('d M, Y') }}</div>
                                <div class="text-slate-400">{{ $lead->created_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl block mb-2">inbox</span>
                                No demo requests yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($demoLeads->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $demoLeads->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
         SECTION 2: Tenant CRM Leads (shared.leads)
    ══════════════════════════════════════════════════════ --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-emerald-600 text-2xl">leaderboard</span>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Tenant CRM Leads</h3>
                <p class="text-xs text-slate-500">Leads added by tenants through the Operations / CRM module</p>
            </div>
            <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                {{ $crmLeads->total() }} total
            </span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 bg-slate-50 border-b border-slate-200 uppercase">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wider">Name & Email</th>
                            <th class="px-6 py-4 font-bold tracking-wider hidden sm:table-cell">Phone</th>
                            <th class="px-6 py-4 font-bold tracking-wider hidden md:table-cell">Company</th>
                            <th class="px-6 py-4 font-bold tracking-wider hidden lg:table-cell">Status & Source</th>
                            <th class="px-6 py-4 font-bold tracking-wider hidden lg:table-cell">Tenant</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($crmLeads as $lead)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $lead->name }}</div>
                                <div class="text-xs text-slate-500">{{ $lead->email }}</div>
                                <div class="text-xs text-slate-400 sm:hidden mt-0.5">{{ $lead->phone }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium hidden sm:table-cell">
                                {{ $lead->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 hidden md:table-cell">
                                {{ $lead->company_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $lead->status === 'new' ? 'bg-green-100 text-green-800' : ($lead->status === 'won' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                                    {{ ucfirst($lead->status ?? 'new') }}
                                </span>
                                <div class="text-xs text-slate-400 mt-1">{{ $lead->source ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-600">
                                    {{ $lead->tenant_id ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs">
                                <div class="font-semibold">{{ \Carbon\Carbon::parse($lead->created_at)->format('d M, Y') }}</div>
                                <div class="text-slate-400">{{ \Carbon\Carbon::parse($lead->created_at)->format('h:i A') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl block mb-2">person_search</span>
                                No CRM leads found in any tenant.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($crmLeads->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $crmLeads->links() }}
            </div>
            @endif
        </div>
    </div>

</x-app-layout>
