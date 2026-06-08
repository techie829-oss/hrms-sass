<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Holiday Calendar</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Official company holidays and restricted days off.</p>
            </div>
            <div class="flex items-center gap-2">
                @can('manage-holidays')
                <button onclick="add_holiday_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-5 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-base">add</span> Add Holiday
                </button>
                @endcan
            </div>
        </div>
    </x-slot>

    {{-- Module Navigation Tabs --}}
    <div class="flex items-center gap-1 mb-8 bg-base-200/50 p-1.5 rounded-[20px] w-fit border border-base-200">
        <a href="{{ route('leave.requests.index') }}" class="px-6 py-2 rounded-[14px] text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('leave.requests.*') ? 'bg-white shadow-sm text-primary' : 'text-base-content/40 hover:text-base-content/70' }}">
            Leave Requests
        </a>
        <a href="{{ route('leave.holidays.index') }}" class="px-6 py-2 rounded-[14px] text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('leave.holidays.*') ? 'bg-white shadow-sm text-primary' : 'text-base-content/40 hover:text-base-content/70' }}">
            Holiday Calendar
        </a>
        <a href="{{ route('leave.comp-off.index') }}" class="px-6 py-2 rounded-[14px] text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('leave.comp-off.*') ? 'bg-white shadow-sm text-primary' : 'text-base-content/40 hover:text-base-content/70' }}">
            Comp-Off Claims
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($holidays as $holiday)
            <div class="card-crm p-6 group relative hover:border-primary/30">
                @can('manage-holidays')
                <form action="{{ route('leave.holidays.destroy', $holiday->id) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100" onclick="return confirm('Delete this holiday?')">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </form>
                @endcan

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl {{ $holiday->is_optional ? 'bg-amber-50 text-amber-600' : 'bg-primary/10 text-primary' }} flex flex-col items-center justify-center border border-current/5">
                        <span class="text-xs font-black uppercase">{{ $holiday->date->format('M') }}</span>
                        <span class="text-lg font-black leading-none">{{ $holiday->date->format('d') }}</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-base-content/90">{{ $holiday->name }}</h4>
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-40 mt-0.5">{{ $holiday->date->format('l') }}</p>
                    </div>
                </div>
                
                @if($holiday->description)
                    <p class="text-xs text-base-content/50 mt-4 leading-relaxed font-medium line-clamp-2">
                        {{ $holiday->description }}
                    </p>
                @endif

                <div class="mt-6 flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $holiday->is_optional ? 'bg-amber-100/50 text-amber-700 border-amber-200' : 'bg-emerald-100/50 text-emerald-700 border-emerald-200' }}">
                        {{ $holiday->is_optional ? 'Optional' : 'Public Holiday' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-20 h-20 rounded-full bg-base-200 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-4xl opacity-20">calendar_today</span>
                </div>
                <h3 class="font-bold text-base-content/60">No holidays found for this year.</h3>
            </div>
        @endforelse
    </div>

    {{-- Add Holiday Modal --}}
    <dialog id="add_holiday_modal" class="modal">
        <div class="modal-box bg-base-100 rounded-[32px] p-8 shadow-2xl border border-base-200 max-w-sm">
            <h3 class="text-xl font-bold text-base-content/90 mb-6">Add New Holiday</h3>
            
            <form action="{{ route('leave.holidays.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Holiday Name</label>
                    <input type="text" name="name" class="input input-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required placeholder="e.g. Christmas Day">
                </div>
                <div class="form-control">
                    <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Date</label>
                    <input type="date" name="date" class="input input-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100" required>
                </div>
                <div class="form-control">
                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-base-200/30 rounded-2xl border border-base-200/50">
                        <input type="checkbox" name="is_optional" value="1" class="checkbox checkbox-primary rounded-lg">
                        <span class="text-xs font-bold text-base-content/70">Mark as Optional Holiday</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="text-[10px] font-black uppercase tracking-widest text-base-content/40 mb-2 ml-1">Description (Optional)</label>
                    <textarea name="description" class="textarea textarea-bordered rounded-2xl bg-base-200/50 border-none focus:bg-base-100 min-h-[80px]" placeholder="Briefly describe the significance..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <button type="submit" class="btn bg-primary hover:bg-primary-focus text-white border-none rounded-2xl font-bold">Save Holiday</button>
                    <form method="dialog"><button class="btn btn-ghost rounded-2xl font-bold w-full">Cancel</button></form>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-base-300/40 backdrop-blur-sm"><button>close</button></form>
    </dialog>
</x-app-layout>
