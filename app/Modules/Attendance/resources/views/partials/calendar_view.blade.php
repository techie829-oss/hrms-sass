@php
    $selectedMonth = request('month', date('Y-m'));
    $carbon = \Carbon\Carbon::parse($selectedMonth);
    $daysInMonth = $carbon->daysInMonth;
    $firstDayOfMonth = $carbon->copy()->firstOfMonth()->dayOfWeek; // 0 (Sun) to 6 (Sat)
    
    // Map logs to dates for easy lookup
    $mappedLogs = $logs->groupBy(fn($l) => $l->date->format('Y-m-d'));
@endphp

<div class="card bg-base-100 shadow-sm border border-base-200 overflow-hidden">
    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 border-b border-base-200">
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="p-3 text-center text-[10px] font-black uppercase tracking-widest bg-base-200/50 border-r border-base-200 last:border-0">
                {{ $day }}
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 auto-rows-fr">
        <!-- Empty slots before the first day -->
        @for($i = 0; $i < $firstDayOfMonth; $i++)
            <div class="min-h-[120px] bg-base-200/20 border-b border-r border-base-200 p-2"></div>
        @endfor

        <!-- Days of the month -->
        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateString = $carbon->copy()->day($day)->format('Y-m-d');
                $dayLogs = $mappedLogs->get($dateString, []);
                $isToday = $dateString == date('Y-m-d');
                $isWeekend = in_array($carbon->copy()->day($day)->dayOfWeek, [0, 6]);
            @endphp
            
            <div class="min-h-[120px] border-b border-r border-base-200 p-2 hover:bg-base-200/10 transition-colors {{ $isToday ? 'bg-primary/5' : '' }} {{ $isWeekend ? 'bg-base-200/10' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-black {{ $isToday ? 'bg-primary text-white w-6 h-6 rounded-lg flex items-center justify-center shadow-lg shadow-primary/20' : 'text-base-content/60' }}">
                        {{ $day }}
                    </span>
                    @if(count($dayLogs) > 0)
                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                    @endif
                </div>

                <div class="flex flex-col gap-1.5">
                    @forelse($dayLogs as $log)
                        @php
                            $statusColors = [
                                'present' => 'bg-success/10 text-success border-success/20',
                                'late' => 'bg-warning/10 text-warning border-warning/20',
                                'absent' => 'bg-error/10 text-error border-error/20',
                            ];
                            $color = $statusColors[$log->status] ?? 'bg-base-200 text-base-content/50 border-base-300';
                        @endphp
                        
                        @if($canViewAll)
                            <!-- For admins, show a compact summary or first employee -->
                            <div class="px-1.5 py-0.5 rounded border {{ $color }} text-[8px] font-black uppercase truncate">
                                {{ $log->employee->first_name }}: {{ $log->check_in ? $log->check_in->format('H:i') : 'IN' }}
                            </div>
                        @else
                            <!-- For employees, show detailed log -->
                            <div class="px-2 py-1 rounded-lg border {{ $color }} flex flex-col gap-0.5 shadow-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-[9px] font-black uppercase tracking-widest">{{ $log->status }}</span>
                                    <span class="text-[8px] opacity-60 font-bold">{{ $log->worked_hours }}h</span>
                                </div>
                                <div class="text-[10px] font-black">
                                    {{ $log->check_in ? $log->check_in->format('H:i') : '--' }} - {{ $log->check_out ? $log->check_out->format('H:i') : '--' }}
                                </div>
                            </div>
                        @endif
                    @empty
                        @if(!$isWeekend && $carbon->copy()->day($day)->isPast())
                             <div class="px-2 py-1 rounded-lg border border-error/20 bg-error/5 text-error flex flex-col gap-0.5 opacity-50 grayscale">
                                <span class="text-[9px] font-black uppercase tracking-widest">Absent</span>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        @endfor

        <!-- Empty slots after the last day -->
        @php
            $remainingSlots = 7 - (($firstDayOfMonth + $daysInMonth) % 7);
            if ($remainingSlots == 7) $remainingSlots = 0;
        @endphp
        @for($i = 0; $i < $remainingSlots; $i++)
            <div class="min-h-[120px] bg-base-200/20 border-b border-r border-base-200 p-2"></div>
        @endfor
    </div>
</div>
