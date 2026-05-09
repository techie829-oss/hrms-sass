@php
    $selectedMonth = request('month', date('Y-m'));
    $carbon = \Carbon\Carbon::parse($selectedMonth);
    $daysInMonth = $carbon->daysInMonth;
    $firstDayOfMonth = $carbon->copy()->firstOfMonth()->dayOfWeek; // 0 (Sun) to 6 (Sat)
    
    // Map logs to dates for easy lookup
    $mappedLogs = $logs->groupBy(fn($l) => $l->date->format('Y-m-d'));
@endphp

<div class="card bg-base-100 shadow-xl shadow-base-content/5 border border-base-200/60 rounded-[32px] overflow-hidden">
    <!-- Calendar Grid Header -->
    <div class="grid grid-cols-7 border-b border-base-200/60 bg-base-200/30">
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="p-4 text-center text-[10px] font-black uppercase tracking-[0.2em] opacity-40 border-r border-base-200/60 last:border-0">
                {{ $day }}
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 auto-rows-fr">
        <!-- Empty slots before the first day -->
        @for($i = 0; $i < $firstDayOfMonth; $i++)
            <div class="min-h-[140px] bg-base-200/10 border-b border-r border-base-200/60 p-3"></div>
        @endfor

        <!-- Days of the month -->
        @for($day = 1; $day <= $daysInMonth; $day++)
            @php
                $dateString = $carbon->copy()->day($day)->format('Y-m-d');
                $dayLogs = $mappedLogs->get($dateString, collect());
                $isToday = $dateString == date('Y-m-d');
                $isWeekend = in_array($carbon->copy()->day($day)->dayOfWeek, [0, 6]);
                $totalHours = $dayLogs->sum('worked_hours');
                $primaryStatus = $dayLogs->isNotEmpty() ? $dayLogs->first()->status : null;
            @endphp
            
            <div class="min-h-[140px] border-b border-r border-base-200/60 p-4 hover:bg-base-200/20 transition-all group {{ $isToday ? 'bg-primary/[0.03]' : '' }} {{ $isWeekend ? 'bg-base-200/10' : 'bg-base-100' }}">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-black {{ $isToday ? 'bg-primary text-white w-7 h-7 rounded-[10px] flex items-center justify-center shadow-lg shadow-primary/30' : 'text-base-content/40 group-hover:text-base-content/80' }} transition-colors">
                        {{ $day }}
                    </span>
                    @if(count($dayLogs) > 0)
                        <div class="flex gap-1">
                            @foreach($dayLogs->take(3) as $l)
                                <span class="w-1.5 h-1.5 rounded-full {{ $l->status == 'present' ? 'bg-success' : ($l->status == 'late' ? 'bg-warning' : 'bg-error') }} ring-2 ring-white"></span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="flex flex-col gap-2">
                    @if(count($dayLogs) > 0)
                        @php
                            $statusColors = [
                                'present' => 'bg-success/10 text-success border-success/20',
                                'late' => 'bg-warning/10 text-warning border-warning/20',
                                'absent' => 'bg-error/10 text-error border-error/20',
                            ];
                            $color = $statusColors[$primaryStatus] ?? 'bg-base-200 text-base-content/50 border-base-300';
                        @endphp
                        
                        <div class="px-3 py-2 rounded-2xl border {{ $color }} flex flex-col items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                            <span class="text-lg font-black leading-none">{{ number_format($totalHours, 1) }}h</span>
                            <span class="text-[8px] font-black uppercase tracking-[0.1em] mt-1 opacity-60">Calculated</span>
                        </div>
                        
                        @if($canViewAll && count($dayLogs) > 1)
                            <div class="text-[8px] font-black uppercase tracking-widest text-center opacity-30">
                                {{ count($dayLogs) }} Entries
                            </div>
                        @endif
                    @else
                        @if(!$isWeekend && $carbon->copy()->day($day)->isPast())
                             <div class="px-3 py-2 rounded-2xl border border-dashed border-error/20 bg-error/[0.02] text-error/30 flex flex-col items-center justify-center opacity-60 grayscale group-hover:grayscale-0 transition-all">
                                <span class="material-symbols-outlined text-sm mb-1">event_busy</span>
                                <span class="text-[8px] font-black uppercase tracking-[0.1em]">No Log</span>
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
            <div class="min-h-[140px] bg-base-200/10 border-b border-r border-base-200/60 p-3"></div>
        @endfor
    </div>
</div>
