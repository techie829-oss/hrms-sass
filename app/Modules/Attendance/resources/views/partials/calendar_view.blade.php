@php
    $selectedMonth = request('month', date('Y-m'));
    $carbon = \Carbon\Carbon::parse($selectedMonth);
    $daysInMonth = $carbon->daysInMonth;
    $firstDayOfMonth = $carbon->copy()->firstOfMonth()->dayOfWeek; // 0 (Sun) to 6 (Sat)
    
    // Map logs to dates for easy lookup
    $mappedLogs = $logs->groupBy(fn($l) => $l->date->format('Y-m-d'));
@endphp

@if($canViewAll && !isset($filters['employee_id']))
    <div class="card bg-base-100 border border-base-200 border-dashed rounded-[40px] p-24 flex flex-col items-center justify-center opacity-40 grayscale shadow-sm">
        <div class="w-20 h-20 rounded-[32px] bg-primary/10 flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-primary text-4xl">person_search</span>
        </div>
        <h4 class="font-black text-sm uppercase tracking-[0.2em] text-primary">Select an Employee</h4>
        <p class="text-[10px] font-bold mt-2 text-center max-w-xs">Calendar view is most effective for tracking individual performance. Please search or select an employee to continue.</p>
    </div>
@else
<div class="flex flex-col gap-6">
    <div class="card bg-base-100 shadow-2xl shadow-base-content/5 border border-base-200/60 rounded-[40px] overflow-hidden">
        <!-- Calendar Header: Weekdays -->
        <div class="grid grid-cols-7 bg-base-100 border-b border-base-200/60 p-2">
            @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $index => $day)
                <div class="p-3 flex justify-center">
                    <div class="px-4 py-2 rounded-full flex items-center gap-2 {{ $index == 0 || $index == 6 ? 'bg-error/10 text-error shadow-sm border border-error/20' : 'bg-base-200/50 text-base-content/40' }} transition-all">
                        <span class="material-symbols-outlined text-xs">
                            {{ $index == 0 || $index == 6 ? 'event_busy' : 'calendar_today' }}
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-[0.1em]">
                            {{ substr($day, 0, 3) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 auto-rows-fr bg-base-200/10">
            <!-- Empty slots before the first day -->
            @for($i = 0; $i < $firstDayOfMonth; $i++)
                <div class="min-h-[150px] bg-base-200/20 border-b border-r border-base-200/40"></div>
            @endfor

            <!-- Days of the month -->
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateObj = $carbon->copy()->day($day);
                    $dateString = $dateObj->format('Y-m-d');
                    $dayLogs = $mappedLogs->get($dateString, collect());
                    $isToday = $dateString == date('Y-m-d');
                    $dayOfWeek = $dateObj->dayOfWeek;
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    $totalHours = $dayLogs->sum('worked_hours');
                    $primaryStatus = $dayLogs->isNotEmpty() ? $dayLogs->first()->status : null;
                    
                    // Logic for "Expected" work day
                    $isPast = $dateObj->isPast();
                    $isFuture = $dateObj->isFuture();
                @endphp
                
                <div class="min-h-[150px] border-b border-r border-base-200/40 p-4 transition-all duration-300 group 
                    {{ $isToday ? 'bg-primary/[0.04] ring-1 ring-inset ring-primary/20' : '' }} 
                    {{ $isWeekend ? 'bg-base-200/30' : 'bg-base-100' }}
                    hover:z-10 hover:shadow-2xl hover:shadow-primary/10 hover:bg-base-100 hover:scale-[1.02] hover:rounded-2xl">
                    
                    <div class="flex items-center justify-between mb-6">
                        <div class="relative">
                            <span class="text-sm font-black transition-all duration-300
                                {{ $isToday ? 'bg-primary text-white w-9 h-9 rounded-[14px] flex items-center justify-center shadow-xl shadow-primary/40' : ($isWeekend ? 'text-error/70' : 'text-base-content/60 group-hover:text-base-content/90') }}">
                                {{ $day }}
                            </span>
                            @if($isToday)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                                </span>
                            @endif
                        </div>
                        
                        @if($dayLogs->isNotEmpty())
                            <div class="flex items-center gap-1 opacity-60">
                                @php
                                    $iconMap = [
                                        'present' => ['i' => 'check_circle', 'c' => 'text-success'],
                                        'late' => ['i' => 'schedule', 'c' => 'text-warning'],
                                        'absent' => ['i' => 'cancel', 'c' => 'text-error'],
                                    ];
                                    $dayStatus = $iconMap[$primaryStatus] ?? ['i' => 'event', 'c' => 'text-base-content/20'];
                                @endphp
                                <span class="material-symbols-outlined text-sm {{ $dayStatus['c'] }}">{{ $dayStatus['i'] }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 mt-auto">
                        @if($dayLogs->isNotEmpty())
                            @php
                                $statusMap = [
                                    'present' => ['color' => 'success', 'icon' => 'check_circle'],
                                    'late' => ['color' => 'warning', 'icon' => 'schedule'],
                                    'absent' => ['color' => 'error', 'icon' => 'cancel'],
                                ];
                                $st = $statusMap[$primaryStatus] ?? ['color' => 'base-content', 'icon' => 'event'];
                            @endphp
                            
                            <a href="{{ route('attendance.show', $dayLogs->first()->id) }}" class="relative p-3 rounded-[20px] bg-{{ $st['color'] }}/10 border border-{{ $st['color'] }}/20 flex flex-col items-center justify-center group/status overflow-hidden hover:bg-{{ $st['color'] }}/20 transition-all active:scale-95 shadow-sm group-hover:scale-105">
                                <span class="material-symbols-outlined text-{{ $st['color'] }} text-[40px] absolute -right-2 -bottom-2 opacity-10 group-hover/status:scale-125 transition-transform">{{ $st['icon'] }}</span>
                                <span class="text-xl font-black text-{{ $st['color'] }} leading-none tracking-tighter">{{ number_format($totalHours, 1) }}h</span>
                                <span class="text-[8px] font-black uppercase tracking-[0.15em] mt-1.5 opacity-60 text-{{ $st['color'] }}">View Details</span>
                            </a>
                        @else
                            @if(!$isWeekend && $isPast && !$isToday)
                                <div class="p-3 rounded-[20px] border border-dashed border-error/30 bg-error/[0.05] flex flex-col items-center justify-center opacity-80 group-hover:opacity-100 transition-opacity">
                                    <span class="text-[9px] font-black text-error uppercase tracking-widest">Absent</span>
                                </div>
                            @elseif($isWeekend)
                                <div class="p-3 flex flex-col items-center justify-center opacity-40 group-hover:opacity-60 transition-opacity text-error/40">
                                    <span class="material-symbols-outlined text-xl">hotel</span>
                                    <span class="text-[8px] font-black uppercase tracking-[0.2em] mt-1">Weekend</span>
                                </div>
                        @endif
                    @endif
                    </div>
                </div>
            @endfor

            <!-- Empty slots after the last day -->
            @php
                $remainingSlots = 7 - (($firstDayOfMonth + $daysInMonth) % 7);
                if ($remainingSlots == 7) $remainingSlots = 0;
            @endphp
            @for($i = 0; $i < $remainingSlots; $i++)
                <div class="min-h-[150px] bg-base-200/20 border-b border-r border-base-200/40"></div>
            @endfor
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="flex flex-wrap items-center justify-center gap-8 p-8 bg-base-100 rounded-[32px] border border-base-200/60 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-success/10 border border-success/20 flex items-center justify-center text-success">
                <span class="material-symbols-outlined text-lg">check_circle</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-40 leading-none mb-1">Present</p>
                <p class="text-xs font-black tracking-tight">Full Working Day</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-warning/10 border border-warning/20 flex items-center justify-center text-warning">
                <span class="material-symbols-outlined text-lg">schedule</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-40 leading-none mb-1">Late</p>
                <p class="text-xs font-black tracking-tight">Punch-in Delay</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-error/10 border border-error/20 flex items-center justify-center text-error">
                <span class="material-symbols-outlined text-lg">cancel</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-40 leading-none mb-1">Absent</p>
                <p class="text-xs font-black tracking-tight">No Logs Found</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-base-200 border border-base-300/40 flex items-center justify-center opacity-40">
                <span class="material-symbols-outlined text-lg">hotel</span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-40 leading-none mb-1">Weekend</p>
                <p class="text-xs font-black tracking-tight">Saturday & Sunday</p>
            </div>
        </div>
    </div>
</div>

@endif
