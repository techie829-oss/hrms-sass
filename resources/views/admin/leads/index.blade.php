<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Demo Requests (Leads)</h2>
                <p class="text-sm text-slate-500 mt-1">Manage and view all incoming demo requests from the website</p>
            </div>
        </div>
    </x-slot>

    <div class="card bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 bg-slate-50 border-b border-slate-200 uppercase">
                    <tr>
                        <th class="px-6 py-4 font-bold tracking-wider">Name & Email</th>
                        <th class="px-6 py-4 font-bold tracking-wider hidden sm:table-cell">Mobile</th>
                        <th class="px-6 py-4 font-bold tracking-wider hidden md:table-cell">Company</th>
                        <th class="px-6 py-4 font-bold tracking-wider hidden lg:table-cell">Status & Source</th>
                        <th class="px-6 py-4 font-bold tracking-wider">Date Requested</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $lead->name }}</div>
                            <div class="text-xs text-slate-500">{{ $lead->email }}</div>
                            <!-- Mobile only visibility for extra fields -->
                            <div class="text-xs text-slate-500 sm:hidden mt-1">{{ $lead->phone }}</div>
                            <div class="text-xs text-slate-500 md:hidden">{{ $lead->company_name ?? 'N/A' }}</div>
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-[10px] font-medium bg-indigo-100 text-indigo-800">
                                Tenant: {{ $lead->tenant_id }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium hidden sm:table-cell">
                            {{ $lead->phone }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 hidden md:table-cell">
                            {{ $lead->company_name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 hidden lg:table-cell">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->status == 'new' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                            <div class="text-xs text-slate-400 mt-1">{{ $lead->source ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs font-medium">
                            <div class="font-semibold">{{ $lead->created_at->format('d M, Y') }}</div>
                            <div class="text-slate-400">{{ $lead->created_at->format('h:i A') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <span class="material-symbols-outlined text-4xl text-slate-300">contact_mail</span>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 mb-1">No Leads Found</h3>
                                <p class="text-sm">There are no demo requests at the moment.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($leads->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $leads->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
