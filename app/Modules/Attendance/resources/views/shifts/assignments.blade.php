<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black font-headline tracking-tight text-on-surface uppercase tracking-tighter">Shift Assignments</h2>
                <p class="text-sm text-on-surface-variant font-medium mt-1">Allocate work schedules to employees for accurate attendance tracking.</p>
            </div>
            <div>
                <a href="{{ route('attendance.index') }}" class="btn btn-ghost border-outline-variant/20 rounded-xl font-bold text-xs uppercase tracking-widest px-6">
                    Back to Registry
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-surface-container-lowest rounded-[2.5rem] border border-outline-variant/15 shadow-xl overflow-hidden">
        <form action="{{ route('attendance.shifts.assignments.update') }}" method="POST">
            @csrf
            <x-table :headers="['Employee', 'Department', 'Current Shift', 'Assign New Shift']" :striped="false">
                @foreach($employees as $index => $employee)
                    <tr class="hover:bg-primary/5 transition-colors border-b border-outline-variant/5">
                        <td class="py-5 px-8">
                            <input type="hidden" name="assignments[{{ $index }}][employee_id]" value="{{ $employee->id }}">
                            <div class="flex items-center gap-4">
                                <div class="avatar placeholder">
                                    <div class="bg-primary/10 text-primary rounded-xl w-10 h-10 font-black text-xs uppercase tracking-widest">
                                        {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="font-black text-on-surface text-sm uppercase tracking-tight">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                    <div class="text-[10px] text-on-surface-variant opacity-60 font-bold uppercase tracking-widest">{{ $employee->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ $employee->department->name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @if($employee->attendanceShift)
                                <div class="badge badge-outline border-primary/30 text-primary font-black text-[9px] px-3 py-2 uppercase tracking-widest">
                                    {{ $employee->attendanceShift->name }} ({{ $employee->attendanceShift->start_time }} - {{ $employee->attendanceShift->end_time }})
                                </div>
                            @else
                                <div class="text-[9px] font-bold text-on-surface-variant opacity-30 uppercase tracking-widest italic">Default (Not Assigned)</div>
                            @endif
                        </td>
                        <td class="px-8">
                            <select name="assignments[{{ $index }}][shift_id]" class="w-full bg-surface-container-low border-transparent focus:border-primary focus:ring-0 rounded-xl p-2 text-on-surface font-bold uppercase tracking-widest text-[10px]">
                                <option value="">Default Shift</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ $employee->attendance_shift_id == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->name }} ({{ $shift->start_time }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            </x-table>
            
            <div class="p-8 bg-surface-container-low border-t border-outline-variant/5 flex justify-end">
                <button type="submit" class="btn btn-primary bg-gradient-to-br from-primary to-secondary border-none rounded-xl font-bold text-xs uppercase tracking-[0.2em] px-10 shadow-lg transition-all hover:scale-105">
                    Save Assignments
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
