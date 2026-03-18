<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Leave Types</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Manage available leave categories for employees.</p>
            </div>
            <button onclick="add_leavetype_modal.showModal()" class="btn btn-primary btn-sm">
                <span class="material-symbols-outlined text-base">add</span> Add Leave Type
            </button>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success mb-6 text-sm font-semibold">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error mb-6 text-sm font-semibold">
            <span class="material-symbols-outlined">error</span>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($leaveTypes as $type)
            <div class="card bg-base-100 shadow-sm border border-base-200 hover:border-primary/30 transition-all flex flex-col justify-between min-h-[180px]">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-[10px] tracking-wider uppercase">
                            {{ $type->code }}
                        </div>
                        <div class="flex gap-2">
                            @if($type->is_paid)
                                <div class="badge badge-success badge-sm whitespace-nowrap">Paid</div>
                            @else
                                <div class="badge badge-ghost badge-sm whitespace-nowrap">Unpaid</div>
                            @endif
                        </div>
                    </div>
                    
                    <h3 class="text-base font-bold mb-1">{{ $type->name }}</h3>
                    <p class="text-xs font-medium leading-relaxed opacity-70 line-clamp-2">
                        {{ $type->description ?? 'No description provided.' }}
                    </p>
                </div>
                
                <div class="px-6 py-4 border-t border-base-200 flex items-center justify-between mt-auto">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-sm">event</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">
                            {{ $type->max_days_per_year }} Days/Yr
                        </span>
                    </div>
                    @if($type->is_carry_forward)
                        <div class="flex items-center gap-1 text-success" title="Balances carry forward to next year">
                            <span class="material-symbols-outlined text-sm">loop</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Carry Fwd</span>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-base-100 rounded-xl border border-base-200 border-dashed">
                <span class="material-symbols-outlined text-4xl text-base-content/30 mb-3">category</span>
                <p class="text-sm font-medium text-base-content/60">No leave types configured yet.</p>
                <button onclick="add_leavetype_modal.showModal()" class="btn btn-outline btn-sm mt-4">
                    Create First Leave Type
                </button>
            </div>
        @endforelse
    </div>

    {{-- Add Modal --}}
    <dialog id="add_leavetype_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_circle</span>
                New Leave Type
            </h3>
            
            <form action="{{ route('leave.types.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-xs uppercase tracking-wider">Type Name</span></label>
                        <input type="text" name="name" class="input input-bordered w-full" placeholder="e.g. Annual Leave" required />
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-xs uppercase tracking-wider">Short Code</span></label>
                        <input type="text" name="code" class="input input-bordered w-full uppercase" placeholder="e.g. AL" maxlength="10" required />
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-wider">Max Days Per Year</span></label>
                    <input type="number" name="max_days_per_year" class="input input-bordered w-full" min="0" placeholder="e.g. 15" required />
                </div>

                <div class="flex flex-col gap-3 mt-2">
                    <label class="label cursor-pointer justify-start gap-4">
                        <input type="checkbox" name="is_paid" value="1" class="checkbox checkbox-primary checkbox-sm" checked />
                        <span class="label-text font-bold text-xs uppercase tracking-wider">Is Paid Leave?</span>
                    </label>

                    <label class="label cursor-pointer justify-start gap-4">
                        <input type="checkbox" name="is_carry_forward" value="1" class="checkbox checkbox-primary checkbox-sm" />
                        <span class="label-text font-bold text-xs uppercase tracking-wider">Carry Forward Balance to Next Year?</span>
                    </label>
                </div>

                <div class="form-control mt-4">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-wider">Description (Optional)</span></label>
                    <textarea name="description" class="textarea textarea-bordered h-24" placeholder="Brief details about this policy..."></textarea>
                </div>

                <div class="modal-action mt-6">
                    <button type="button" class="btn btn-ghost" onclick="add_leavetype_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Leave Type</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
