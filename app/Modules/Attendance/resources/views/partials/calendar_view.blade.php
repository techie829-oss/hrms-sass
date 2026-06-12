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
    <div class="bg-white border border-slate-200 border-dashed rounded-xl p-24 flex flex-col items-center justify-center opacity-40 grayscale shadow-sm">
        <div class="w-20 h-20 rounded-[32px] bg-primary/10 flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-primary text-4xl">person_search</span>
        </div>
        <h4 class="font-black text-sm uppercase tracking-[0.2em] text-primary">Select an Employee</h4>
        <p class="text-[10px] font-bold mt-2 text-center max-w-xs">Calendar view is most effective for tracking individual performance. Please search or select an employee to continue.</p>
    </div>
@else
<div class="flex flex-col gap-6">
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <!-- Calendar Header: Weekdays -->
        <div class="grid grid-cols-7 bg-slate-50 border-b border-slate-200 p-1">
            @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $index => $day)
                <div class="p-2 flex justify-center">
                    <div class="px-3 py-1.5 rounded-xl flex items-center gap-1.5 {{ $index == 0 || $index == 6 ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-slate-100/60 text-slate-500' }} transition-all">
                        <span class="material-symbols-outlined text-[10px]">
                            {{ $index == 0 || $index == 6 ? 'event_busy' : 'calendar_today' }}
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">
                            {{ substr($day, 0, 3) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 auto-rows-fr bg-slate-50/20">
            <!-- Empty slots before the first day -->
            @for($i = 0; $i < $firstDayOfMonth; $i++)
                <div class="min-h-[120px] bg-slate-50/30 border-b border-r border-slate-100"></div>
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
                
                <div class="min-h-[120px] border-b border-r border-slate-100 p-3.5 transition-all duration-200 group 
                    {{ $isToday ? 'bg-primary/[0.03] ring-1 ring-inset ring-primary/10' : '' }} 
                    {{ $isWeekend ? 'bg-slate-50/40' : 'bg-white' }}
                    hover:z-10 hover:shadow-lg hover:shadow-primary/5 hover:bg-white hover:scale-[1.01] hover:rounded-xl">
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="relative">
                            <span class="text-xs transition-all duration-200
                                {{ $isToday ? 'bg-primary text-white w-6 h-6 rounded-lg flex items-center justify-center shadow-sm shadow-primary/30 font-bold' : ($isWeekend ? 'text-rose-500 font-bold' : 'text-slate-600 group-hover:text-slate-800 font-bold') }}">
                                {{ $day }}
                            </span>
                        </div>
                        
                        @if($summary)
                            @php
                                $statusIconMap = [
                                    'present'  => ['i' => 'check_circle', 'c' => 'text-emerald-500'],
                                    'late'     => ['i' => 'schedule', 'c' => 'text-amber-500'],
                                    'half_day' => ['i' => 'circle_half', 'c' => 'text-sky-500'],
                                    'absent'   => ['i' => 'cancel', 'c' => 'text-rose-500'],
                                ];
                                $sIcon = $statusIconMap[$summary->status] ?? ['i' => 'event', 'c' => 'opacity-20'];
                            @endphp
                            <span class="material-symbols-outlined text-sm font-bold {{ $sIcon['c'] }}">{{ $sIcon['i'] }}</span>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2 mt-auto">
                        @if($summary)
                            {{-- Day Type Badge --}}
                            <div class="flex flex-col gap-0.5">
                                @php
                                    $typeMap = [
                                        'full_day'    => ['l' => 'Full Day', 'c' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60'],
                                        'half_day'    => ['l' => 'Half Day', 'c' => 'bg-sky-50 text-sky-700 border-sky-200/60'],
                                        'quarter_day' => ['l' => 'Short Day', 'c' => 'bg-amber-50 text-amber-700 border-amber-200/60'],
                                        'absent'      => ['l' => 'Absent', 'c' => 'bg-rose-50 text-rose-700 border-rose-200/60'],
                                    ];
                                    $t = $typeMap[$summary->day_type] ?? null;
                                @endphp
                                @if($t)
                                    <span class="px-1.5 py-0.5 rounded-lg text-[9px] font-bold uppercase tracking-wider border {{ $t['c'] }} w-max">
                                        {{ $t['l'] }}
                                    </span>
                                @endif

                                {{-- Worked Hours --}}
                                @if($totalHours > 0)
                                    <span class="text-[10px] font-semibold text-slate-500 mt-0.5">
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
                                                'late_arrived'     => ['i' => 'timer', 'c' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                                'checkout_missing' => ['i' => 'warning', 'c' => 'bg-rose-500 text-white border-rose-600 animate-pulse'],
                                                'overtime'         => ['i' => 'add_task', 'c' => 'bg-purple-50 text-purple-700 border-purple-200'],
                                                'early_leave'      => ['i' => 'logout', 'c' => 'bg-orange-50 text-orange-700 border-orange-200'],
                                                'multi_session'    => ['i' => 'repeat', 'c' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                            ];
                                            $tg = $tagMap[$tag] ?? ['i' => 'label', 'c' => 'bg-slate-100 text-slate-600 border-slate-200'];
                                        @endphp
                                        <div class="flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[8px] font-bold uppercase tracking-wider border {{ $tg['c'] }}" title="{{ str_replace('_', ' ', $tag) }}">
                                            <span class="material-symbols-outlined text-[9px]">{{ $tg['i'] }}</span>
                                            {{ explode('_', $tag)[0] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($isPast && !$isWeekend)
                            <div class="flex flex-col items-center justify-center py-2 bg-rose-50/30 rounded-xl border border-dashed border-rose-200/60 opacity-60">
                                <span class="text-[8px] font-bold uppercase tracking-wider text-rose-500">Absent</span>
                            </div>
                        @elseif($isWeekend)
                            <div class="flex flex-col items-center justify-center py-2 opacity-30 text-slate-400">
                                <span class="material-symbols-outlined text-base">bedtime</span>
                                <span class="text-[7px] font-bold uppercase tracking-wider mt-0.5">Weekend</span>
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
                <div class="min-h-[120px] bg-slate-50/30 border-b border-r border-slate-100"></div>
            @endfor
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="flex flex-wrap items-center justify-center gap-8 p-6 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200/60 flex items-center justify-center text-emerald-600">
                <span class="material-symbols-outlined text-lg">check_circle</span>
            </div>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 leading-none mb-1">Present</p>
                <p class="text-xs font-bold text-slate-700 tracking-tight">Full Working Day</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-600">
                <span class="material-symbols-outlined text-lg">schedule</span>
            </div>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 leading-none mb-1">Late</p>
                <p class="text-xs font-bold text-slate-700 tracking-tight">Punch-in Delay</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200/60 flex items-center justify-center text-rose-600">
                <span class="material-symbols-outlined text-lg">cancel</span>
            </div>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 leading-none mb-1">Absent</p>
                <p class="text-xs font-bold text-slate-700 tracking-tight">No Logs Found</p>
            </div>
        </div>
    </div>
</div>
@endif
