<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Manual Attendance</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Record a manual check-in entry.</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-6 md:p-8">
                <form action="{{ route('attendance.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="form-control w-full">
                        <label for="employee_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Select Employee <span class="text-rose-500">*</span></label>
                        <select name="employee_id" id="employee_id" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-2 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                            <option value="">Choose an employee...</option>
                            @foreach(App\Modules\HR\Models\Employee::all() as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }} ({{ $employee->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label for="date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                        </div>

                        <div class="form-control w-full">
                            <label for="check_in" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Check In Time <span class="text-rose-500">*</span></label>
                            <input type="time" name="check_in" id="check_in" value="{{ date('H:i') }}" class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-gray-900 bg-white transition-all shadow-sm" required>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label for="remarks" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" placeholder="Reason for manual entry..." class="block w-full border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3.5 py-2 text-xs text-gray-900 bg-white placeholder-slate-400 transition-all shadow-sm"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">Record Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
