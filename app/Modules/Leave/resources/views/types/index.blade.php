<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Leave Types</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Configure categories and quotas for employee time-off.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('leave.requests.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-3 shadow-sm flex items-center justify-center">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                </a>
                <button onclick="add_leavetype_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-4 shadow-sm shadow-primary/20 text-white font-semibold text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">add</span> Add New Type
                </button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leaveTypes as $type)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:border-primary/50 group flex flex-col h-full transition-all">
                <div class="p-6 flex-1">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center border border-primary-200/20 font-bold text-xs uppercase">
                            {{ $type->code }}
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold border {{ $type->is_paid ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60' : 'bg-slate-50 text-slate-400 border-slate-200/50' }} uppercase">
                            {{ $type->is_paid ? 'Paid' : 'Unpaid' }}
                        </span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-slate-800 tracking-tight mb-1.5">{{ $type->name }}</h3>
                    <p class="text-xs text-slate-500 font-semibold leading-relaxed line-clamp-2">
                        {{ $type->description ?? 'Standard leave policy with defined quotas and carry-forward rules.' }}
                    </p>
                </div>
                
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between mt-auto">
                    <div class="flex items-center gap-2 text-slate-600 text-xs font-semibold">
                        <span class="material-symbols-outlined text-primary text-base">event_note</span>
                        <span>
                            {{ $type->max_days_per_year }} Days / Year
                        </span>
                    </div>
                    @if($type->is_carry_forward)
                        <div class="flex items-center gap-1 text-emerald-600 text-[10px] font-bold uppercase tracking-wider" title="Balances carry forward to next year">
                            <span class="material-symbols-outlined text-sm">sync_alt</span>
                            <span>Carry Over</span>
                        </div>
                    @else
                        <div class="flex items-center gap-1 text-slate-400 text-[10px] font-bold uppercase tracking-wider" title="Balances reset every year">
                            <span class="material-symbols-outlined text-sm">restart_alt</span>
                            <span>Reset</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200/60 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-slate-400">category</span>
                </div>
                <h3 class="font-bold text-sm text-slate-700">No Leave Types Yet</h3>
                <p class="text-xs text-slate-500 font-semibold mb-6 max-w-xs mx-auto mt-1 leading-relaxed">Configure your company's leave policies to start managing employee time-off requests.</p>
                <button onclick="add_leavetype_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-6">
                    Create First Leave Type
                </button>
            </div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <dialog id="add_leavetype_modal" class="modal">
        <div class="modal-box bg-white border border-slate-200 rounded-xl shadow-2xl p-6 md:p-8 max-w-lg text-left">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center border border-primary-200/20">
                        <span class="material-symbols-outlined text-primary text-lg">add_circle</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">New Leave Type</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Add a new category to your leave policy</p>
                    </div>
                </div>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('leave.types.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-control">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Type Name</label>
                        <input type="text" name="name" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" placeholder="e.g. Annual Leave" required />
                    </div>

                    <div class="form-control">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Short Code</label>
                        <input type="text" name="code" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm uppercase" placeholder="e.g. AL" maxlength="10" required />
                    </div>
                </div>

                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Max Days Per Year</label>
                    <input type="number" name="max_days_per_year" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" min="0" placeholder="e.g. 15" required />
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-4">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-slate-700">Paid Leave</span>
                            <span class="text-[10px] font-semibold text-slate-400 mt-0.5">Salary is not deducted for these days</span>
                        </div>
                        <input type="checkbox" name="is_paid" value="1" class="checkbox checkbox-primary rounded-lg checkbox-sm" checked />
                    </label>

                    <div class="h-px bg-slate-200 w-full"></div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-3 p-0">
                            <input type="checkbox" name="is_carry_forward" value="1" class="checkbox checkbox-primary rounded-lg checkbox-sm" />
                            <span class="text-xs font-bold text-slate-700">Carry Forward Balances</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-3 p-0">
                            <input type="checkbox" name="applicable_in_probation" value="1" class="checkbox checkbox-primary rounded-lg checkbox-sm" />
                            <span class="text-xs font-bold text-slate-700">Applicable in Probation</span>
                        </label>
                    </div>
                </div>

                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                    <textarea name="description" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm min-h-[80px]" placeholder="Brief details about this policy..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('add_leavetype_modal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">Save Policy</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/30 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
