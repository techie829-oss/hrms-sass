<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface">Manual Attendance Log</h2>
        <p class="text-sm text-on-surface-variant font-medium mt-1">Manually record a check-in for an employee.</p>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 premium-shadow overflow-hidden p-8">
            <form action="{{ route('attendance.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="employee_id" class="text-sm font-bold text-on-surface ml-1 uppercase tracking-widest">Select Architect</label>
                    <select name="employee_id" id="employee_id" class="w-full bg-surface-container-low border border-outline-variant/20 rounded-2xl px-5 py-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium appearance-none">
                        <option value="">Choose an employee...</option>
                        @foreach(App\Modules\HR\Models\Employee::all() as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }} ({{ $employee->employee_id }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="date" class="text-sm font-bold text-on-surface ml-1 uppercase tracking-widest">Date</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="w-full bg-surface-container-low border border-outline-variant/20 rounded-2xl px-5 py-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                    </div>

                    <div class="space-y-2">
                        <label for="check_in" class="text-sm font-bold text-on-surface ml-1 uppercase tracking-widest">Check In Time</label>
                        <input type="time" name="check_in" id="check_in" value="{{ date('H:i') }}" class="w-full bg-surface-container-low border border-outline-variant/20 rounded-2xl px-5 py-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="remarks" class="text-sm font-bold text-on-surface ml-1 uppercase tracking-widest">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="3" placeholder="Reason for manual entry..." class="w-full bg-surface-container-low border border-outline-variant/20 rounded-2xl px-5 py-4 text-sm focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-medium"></textarea>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('attendance.index') }}" class="btn btn-ghost rounded-xl font-bold text-xs uppercase tracking-widest px-8">Cancel</a>
                    <button type="submit" class="btn btn-primary primary-gradient border-none rounded-xl font-bold text-xs uppercase tracking-widest px-10 shadow-lg">Save Log Entry</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
