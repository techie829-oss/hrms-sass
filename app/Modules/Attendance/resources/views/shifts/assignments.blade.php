<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-on-surface tracking-tight">Shift Assignments</h2>
                <p class="text-xs font-medium mt-0.5 text-on-surface-variant">Allocate work schedules to employees for accurate attendance tracking.</p>
            </div>
            <div>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <form action="{{ route('attendance.shifts.assignments.update') }}" method="POST">
            @csrf
            <div class="table-crm">
                <x-table :headers="['Employee', 'Department', 'Current Shift', 'Assign New Shift']" :striped="false">
                    @foreach($employees as $index => $employee)
                        <tr class="hover:bg-slate-50/50 transition-all border-b border-slate-100 last:border-0 group">
                            <td class="py-3 px-4">
                                <input type="hidden" name="assignments[{{ $index }}][employee_id]" value="{{ $employee->id }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0 border border-slate-200/60 shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                                        @if(!empty($employee->profile_photo))
                                            <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            @php
                                                $nameParts = explode(' ', trim($employee->full_name ?? 'U'));
                                                $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                            @endphp
                                            {{ $initials }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-slate-700">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                        <div class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">{{ $employee->employee_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600 font-semibold">
                                {{ $employee->department->name ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4">
                                @if($employee->attendanceShift)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-primary-50 text-primary-600 border border-primary-100">
                                        {{ $employee->attendanceShift->name }} ({{ \Carbon\Carbon::parse($employee->attendanceShift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($employee->attendanceShift->end_time)->format('H:i') }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-50 text-slate-400 border border-slate-200/50 italic">
                                        Default (Not Assigned)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <select name="assignments[{{ $index }}][shift_id]" class="block w-full max-w-xs border border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 rounded-xl px-3 py-1.5 text-xs text-slate-700 bg-white font-semibold shadow-sm transition-all">
                                    <option value="">Default Shift</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ $employee->attendance_shift_id == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            </div>
            
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 shadow-sm text-xs font-semibold">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm rounded-xl px-5 shadow-sm shadow-primary/20 text-white font-semibold text-xs">
                    Save Assignments
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
