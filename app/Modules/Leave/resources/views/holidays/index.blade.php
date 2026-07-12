@php
use App\Core\Constants\PermissionConstants;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Holiday Calendar</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Official company holidays and restricted days off.</p>
            </div>
            <div class="flex items-center gap-2">
                @can(PermissionConstants::MANAGE_HOLIDAYS)
                <button onclick="add_holiday_modal.showModal()" class="btn btn-primary btn-sm rounded-xl px-4 shadow-sm shadow-primary/20 text-white font-semibold text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">add</span> Add Holiday
                </button>
                @endcan
            </div>
        </div>
    </x-slot>

    {{-- Module Navigation Tabs --}}
    <div class="flex items-center gap-1 mb-6 bg-slate-100 p-1 rounded-xl border border-slate-200 w-fit">
        <a href="{{ route('leave.requests.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all {{ request()->routeIs('leave.requests.*') ? 'bg-white shadow-sm text-primary border border-slate-200/60' : 'text-slate-500 hover:text-slate-800' }}">
            Leave Requests
        </a>
        <a href="{{ route('leave.holidays.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all {{ request()->routeIs('leave.holidays.*') ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-800' }}">
            Holiday Calendar
        </a>
        <a href="{{ route('leave.comp-off.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold tracking-tight transition-all {{ request()->routeIs('leave.comp-off.*') ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-800' }}">
            Comp-Off Claims
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($holidays as $holiday)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 group relative hover:border-primary/50 transition-all flex flex-col justify-between">
                @can(PermissionConstants::MANAGE_HOLIDAYS)
                <form action="{{ route('leave.holidays.destroy', $holiday->id) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition-colors" onclick="return confirm('Delete this holiday?')">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </form>
                @endcan

                <div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl {{ $holiday->is_optional ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-primary/10 text-primary border border-primary-200/20' }} flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-[9px] font-black uppercase tracking-wider leading-none">{{ $holiday->date->format('M') }}</span>
                            <span class="text-base font-bold leading-none mt-1">{{ $holiday->date->format('d') }}</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-slate-800">{{ $holiday->name }}</h4>
                            <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $holiday->date->format('l') }}</p>
                        </div>
                    </div>
                    
                    @if($holiday->description)
                        <p class="text-[11px] text-slate-500 mt-4 leading-relaxed font-semibold line-clamp-2">
                            {{ $holiday->description }}
                        </p>
                    @endif
                </div>

                <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-bold border {{ $holiday->is_optional ? 'bg-amber-50 text-amber-700 border-amber-200/60' : 'bg-emerald-50 text-emerald-700 border-emerald-200/60' }} uppercase">
                        {{ $holiday->is_optional ? 'Optional' : 'Public Holiday' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200/60 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl text-slate-400">calendar_today</span>
                </div>
                <h3 class="font-bold text-sm text-slate-700">No holidays found for this year.</h3>
            </div>
        @endforelse
    </div>

    {{-- Add Holiday Modal --}}
    <dialog id="add_holiday_modal" class="modal">
        <div class="modal-box bg-white border border-slate-200 rounded-xl shadow-2xl p-6 md:p-8 max-w-sm text-left">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900">Add New Holiday</h3>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            
            <form action="{{ route('leave.holidays.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Holiday Name</label>
                    <input type="text" name="name" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required placeholder="e.g. Christmas Day">
                </div>
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date</label>
                    <input type="date" name="date" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                </div>
                <div class="form-control">
                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <input type="checkbox" name="is_optional" value="1" class="checkbox checkbox-primary checkbox-sm rounded-md">
                        <span class="text-xs font-bold text-slate-700">Mark as Optional Holiday</span>
                    </label>
                </div>
                <div class="form-control">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                    <textarea name="description" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm min-h-[80px]" placeholder="Briefly describe the significance..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold" onclick="document.getElementById('add_holiday_modal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">Save Holiday</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/30 backdrop-blur-sm"><button>close</button></form>
    </dialog>
</x-app-layout>
