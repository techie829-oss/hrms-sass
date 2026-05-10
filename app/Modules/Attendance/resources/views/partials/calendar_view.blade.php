@php
    $selectedMonth = request('month', date('Y-m'));
    $carbon = \Carbon\Carbon::parse($selectedMonth);
    $daysInMonth = $carbon->daysInMonth;
    $firstDayOfMonth = $carbon->copy()->firstOfMonth()->dayOfWeek; // 0 (Sun) to 6 (Sat)
    
    // Map logs and summaries to dates
    $mappedLogs = $logs->groupBy(fn($l) => $l->date->format('Y-m-d'));
    $mappedSummaries = $summaries->groupBy(fn($s) => $s->date->format('Y-m-d'));
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
                    $summary = $mappedSummaries->get($dateString, collect())->first();
                    
                    $isToday = $dateString == date('Y-m-d');
                    $dayOfWeek = $dateObj->dayOfWeek;
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    
                    $totalHours = $summary ? $summary->total_worked_hours : 0;
                    
                    $isPast = $dateObj->isPast();
                    $isFuture = $dateObj->isFuture();
                @endphp
                
                <div class="min-h-[150px] border-b border-r border-base-200/40 p-4 transition-all duration-300 group 
                    {{ $isToday ? 'bg-primary/[0.04] ring-1 ring-inset ring-primary/20' : '' }} 
                    {{ $isWeekend ? 'bg-base-200/30' : 'bg-base-100' }}
                    hover:z-10 hover:shadow-2xl hover:shadow-primary/10 hover:bg-base-100 hover:scale-[1.02] hover:rounded-2xl">
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="relative">
                            <span class="text-sm font-black transition-all duration-300
                                {{ $isToday ? 'bg-primary text-white w-9 h-9 rounded-[14px] flex items-center justify-center shadow-xl shadow-primary/40' : ($isWeekend ? 'text-error/70' : 'text-base-content/60 group-hover:text-base-content/90') }}">
                                {{ $day }}
                            </span>
                        </div>
                        
                        @if($summary)
                            @php
                                $statusIconMap = [
                                    'present'  => ['i' => 'check_circle', 'c' => 'text-success'],
                                    'late'     => ['i' => 'schedule', 'c' => 'text-warning'],
                                    'half_day' => ['i' => 'circle_half', 'c' => 'text-info'],
                                    'absent'   => ['i' => 'cancel', 'c' => 'text-error'],
                                ];
                                $sIcon = $statusIconMap[$summary->status] ?? ['i' => 'event', 'c' => 'opacity-20'];
                            @endphp
                            <span class="material-symbols-outlined text-base font-black {{ $sIcon['c'] }}">{{ $sIcon['i'] }}</span>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2 mt-auto">
                        @if($summary)
                            {{-- Day Type Badge --}}
                            <div class="flex flex-col gap-1">
                                @php
                                    $typeMap = [
                                        'full_day'    => ['l' => 'Full Day', 'c' => 'bg-success/20 text-success border-success/30'],
                                        'half_day'    => ['l' => 'Half Day', 'c' => 'bg-info/20 text-info border-info/30'],
                                        'quarter_day' => ['l' => 'Short Day', 'c' => 'bg-warning/20 text-warning border-warning/30'],
                                        'absent'      => ['l' => 'Absent', 'c' => 'bg-error/20 text-error border-error/30'],
                                    ];
                                    $t = $typeMap[$summary->day_type] ?? null;
                                @endphp
                                @if($t)
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $t['c'] }} w-max">
                                        {{ $t['l'] }}
                                    </span>
                                @endif

                                {{-- Worked Hours --}}
                                @if($totalHours > 0)
                                    <span class="text-[10px] font-mono font-black text-base-content/60 mt-0.5">
                                        {{ $summary->formatted_hours }}
                                    </span>
                                @endif
                            </div>

                            {{-- Smart Tags --}}
                            @if($summary->tags && count($summary->tags) > 0)
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($summary->tags as $tag)
                                        @php
                                            $tagMap = [
                                                'late_arrived'     => ['i' => 'timer', 'c' => 'bg-amber-100 text-amber-800 border-amber-300'],
                                                'checkout_missing' => ['i' => 'warning', 'c' => 'bg-red-600 text-white border-red-700 animate-pulse'],
                                                'overtime'         => ['i' => 'add_task', 'c' => 'bg-purple-100 text-purple-800 border-purple-300'],
                                                'early_leave'      => ['i' => 'logout', 'c' => 'bg-orange-100 text-orange-800 border-orange-300'],
                                                'multi_session'    => ['i' => 'repeat', 'c' => 'bg-blue-100 text-blue-800 border-blue-300'],
                                            ];
                                            $tg = $tagMap[$tag] ?? ['i' => 'label', 'c' => 'bg-base-200 text-base-content border-base-300'];
                                        @endphp
                                        <div class="flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[8px] font-black uppercase tracking-tighter border {{ $tg['c'] }}" title="{{ str_replace('_', ' ', $tag) }}">
                                            <span class="material-symbols-outlined text-[10px]">{{ $tg['i'] }}</span>
                                            {{ explode('_', $tag)[0] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($isPast && !$isWeekend)
                            <div class="flex flex-col items-center justify-center py-2 bg-error/5 rounded-xl border border-dashed border-error/20 opacity-40">
                                <span class="text-[8px] font-black uppercase tracking-widest text-error">Absent</span>
                            </div>
                        @elseif($isWeekend)
                            <div class="flex flex-col items-center justify-center py-2 opacity-20">
                                <span class="material-symbols-outlined text-lg">hotel</span>
                                <span class="text-[7px] font-black uppercase tracking-widest">Weekend</span>
                            </div>
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
    </div>
</div>
@endif
