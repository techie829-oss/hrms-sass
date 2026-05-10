<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-base-content/90 tracking-tight">Leave Types</h2>
                <p class="text-xs font-medium mt-0.5 opacity-50">Configure categories and quotas for employee time-off.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('leave.requests.index') }}" class="btn btn-ghost btn-sm btn-outline border-base-300 rounded-xl px-3">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                </a>
                <button onclick="add_leavetype_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-5 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-base">add</span> Add New Type
                </button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($leaveTypes as $type)
            <div class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden hover:border-primary/30 transition-all group flex flex-col h-full">
                <div class="card-body p-8 flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-[22px] bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                            <span class="text-xs font-black uppercase tracking-tighter">{{ $type->code }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $type->is_paid ? 'bg-success/10 text-success border-success/20' : 'bg-base-200 text-base-content/50 border-base-300/50' }}">
                            {{ $type->is_paid ? 'Paid' : 'Unpaid' }}
                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-base-content/90 tracking-tight mb-2">{{ $type->name }}</h3>
                    <p class="text-xs font-medium leading-relaxed opacity-50 line-clamp-2">
                        {{ $type->description ?? 'Standard leave policy with defined quotas and carry-forward rules.' }}
                    </p>
                </div>
                
                <div class="px-8 py-5 bg-base-200/30 border-t border-base-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-base">event_note</span>
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-70">
                            {{ $type->max_days_per_year }} Days / Year
                        </span>
                    </div>
                    @if($type->is_carry_forward)
                        <div class="flex items-center gap-1.5 text-success" title="Balances carry forward to next year">
                            <span class="material-symbols-outlined text-base">sync_alt</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Carry Over</span>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 opacity-30" title="Balances reset every year">
                            <span class="material-symbols-outlined text-base">restart_alt</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Reset</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center bg-base-100 rounded-[32px] border-2 border-dashed border-base-200">
                <div class="w-16 h-16 rounded-full bg-base-200 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl opacity-30">category</span>
                </div>
                <h3 class="text-lg font-bold opacity-70 mb-2">No Leave Types Yet</h3>
                <p class="text-xs font-medium opacity-40 mb-8 max-w-xs mx-auto">Configure your company's leave policies to start managing employee time-off requests.</p>
                <button onclick="add_leavetype_modal.showModal()" class="btn btn-primary rounded-xl px-8">
                    Create First Leave Type
                </button>
            </div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <dialog id="add_leavetype_modal" class="modal">
        <div class="modal-box rounded-[32px] p-8 max-w-lg shadow-2xl border border-base-300">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-6 top-6">✕</button>
            </form>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-[22px] bg-primary/10 flex items-center justify-center border border-primary/20">
                    <span class="material-symbols-outlined text-primary">add_circle</span>
                </div>
                <div>
                    <h3 class="font-bold text-xl tracking-tight">New Leave Type</h3>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-40 mt-1">Add a new category to your leave policy</p>
                </div>
            </div>
            
            <form action="{{ route('leave.types.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-black text-[10px] uppercase tracking-widest opacity-60">Type Name</span></label>
                        <input type="text" name="name" class="input input-bordered w-full rounded-2xl font-bold text-xs" placeholder="e.g. Annual Leave" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-black text-[10px] uppercase tracking-widest opacity-60">Short Code</span></label>
                        <input type="text" name="code" class="input input-bordered w-full rounded-2xl font-bold text-xs uppercase" placeholder="e.g. AL" maxlength="10" required />
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-black text-[10px] uppercase tracking-widest opacity-60">Max Days Per Year</span></label>
                    <input type="number" name="max_days_per_year" class="input input-bordered w-full rounded-2xl font-bold text-xs" min="0" placeholder="e.g. 15" required />
                </div>

                <div class="bg-base-200/50 p-6 rounded-3xl border border-base-200 space-y-4">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-black uppercase tracking-widest text-base-content/80">Paid Leave</span>
                            <span class="text-[9px] font-medium opacity-50 mt-1">Salary is not deducted for these days</span>
                        </div>
                        <input type="checkbox" name="is_paid" value="1" class="checkbox checkbox-primary rounded-lg" checked />
                    </label>

                    <div class="h-px bg-base-200 w-full"></div>

                    <label class="flex items-center justify-between cursor-pointer">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-black uppercase tracking-widest text-base-content/80">Carry Forward</span>
                            <span class="text-[9px] font-medium opacity-50 mt-1">Balance moves to next calendar year</span>
                        </div>
                        <input type="checkbox" name="is_carry_forward" value="1" class="checkbox checkbox-primary rounded-lg" />
                    </label>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-black text-[10px] uppercase tracking-widest opacity-60">Description (Optional)</span></label>
                    <textarea name="description" class="textarea textarea-bordered rounded-2xl h-24 font-medium text-xs leading-relaxed" placeholder="Brief details about this policy..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-base-200/60">
                    <button type="button" class="btn btn-ghost rounded-xl px-6" onclick="add_leavetype_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-xl px-8 shadow-lg shadow-primary/20">Save Policy</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
