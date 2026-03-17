<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.index') }}" class="btn btn-ghost btn-xs btn-circle">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                </a>
                <h2 class="text-xl font-bold text-on-surface">Attendance Kiosk</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[9px] font-black uppercase tracking-widest opacity-40" id="live-clock"></span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6" x-data="kioskApp({
        isKioskEnabled: {{ ($policy->is_kiosk_enabled ?? true) ? 'true' : 'false' }},
        requirePhoto: {{ ($policy->kiosk_require_photo ?? true) ? 'true' : 'false' }},
        requireLocation: {{ ($policy->kiosk_require_location ?? true) ? 'true' : 'false' }}
    })" x-init="init()">
        
        {{-- Kiosk Disabled Overlay --}}
        <div x-show="!isKioskEnabled" class="bg-surface-container-lowest rounded-2xl border-2 border-error p-10 text-center shadow-2xl" x-cloak>
            <span class="material-symbols-outlined text-6xl text-error mb-4">block</span>
            <h2 class="text-2xl font-black text-on-surface uppercase tracking-widest">Kiosk Access Disabled</h2>
            <p class="text-xs font-bold text-on-surface-variant opacity-60 mt-2">
                The attendance kiosk has been disabled by the administrator for this policy.
            </p>
            <a href="{{ route('attendance.index') }}" class="btn btn-outline btn-sm mt-6 rounded-xl font-black uppercase tracking-widest px-8">
                Go to Attendance Logs
            </a>
        </div>

        <div x-show="isKioskEnabled" class="space-y-6">

        {{-- Success / Error Alerts --}}
        @if(session('success'))
            <div class="alert alert-success shadow-lg text-xs font-bold" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error shadow-lg text-xs font-bold" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
                <span class="material-symbols-outlined text-sm">error</span>
                {{ session('error') }}
            </div>
        @endif

        {{-- Main Kiosk Card --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-xl overflow-hidden">
            {{-- Header Strip --}}
            <div class="bg-gradient-to-r from-primary/10 via-secondary/5 to-accent/10 p-6 border-b border-outline-variant/5">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-surface-container-lowest border-2 border-surface-container-lowest shadow-lg flex items-center justify-center overflow-hidden">
                        @if($user->employee?->profile_photo)
                            <img src="{{ asset('storage/' . $user->employee->profile_photo) }}" class="w-full h-full object-cover" />
                        @else
                            <span class="material-symbols-outlined text-3xl text-primary/40">person</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-on-surface">{{ $user->name }}</h3>
                        <p class="text-[10px] font-bold text-on-surface-variant opacity-60 uppercase tracking-widest mt-0.5">
                            {{ $employee?->designation?->name ?? 'Employee' }} · {{ $employee?->department?->name ?? 'General' }}
                        </p>
                        <p class="text-[9px] font-bold text-on-surface-variant/40 uppercase tracking-widest mt-1">
                            ID: {{ $employee?->employee_id ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="ml-auto text-right">
                        <div class="text-3xl font-black text-on-surface" id="main-clock">--:--</div>
                        <div class="text-[9px] font-bold text-on-surface-variant opacity-40 uppercase tracking-widest">{{ now()->format('l, d M Y') }}</div>
                        @if($shift)
                            <div class="mt-1 badge badge-primary badge-outline text-[8px] font-black px-2 py-0.5 h-auto uppercase">
                                {{ $shift->name }} · {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Clock In/Out Section --}}
            <div class="p-8">
                <div class="flex flex-col lg:flex-row items-center gap-8 justify-center">
                    {{-- Camera Preview --}}
                    <div class="flex flex-col items-center gap-3">
                        <div class="relative w-48 h-48 rounded-2xl overflow-hidden bg-black/90 border-2 border-outline-variant/20 shadow-xl">
                            <video x-ref="cameraVideo" autoplay playsinline muted class="w-full h-full object-cover" style="transform: scaleX(-1);"></video>
                            <canvas x-ref="cameraCanvas" class="hidden"></canvas>
                            {{-- Captured photo overlay --}}
                            <img x-ref="capturedPhoto" x-show="photoCaptured" class="absolute inset-0 w-full h-full object-cover" style="transform: scaleX(-1); display:none;" />
                            {{-- Camera status overlay --}}
                            <div x-show="!cameraReady && !cameraError" class="absolute inset-0 flex items-center justify-center bg-black/80">
                                <div class="text-center">
                                    <span class="material-symbols-outlined text-3xl text-white/40 animate-pulse">videocam</span>
                                    <p class="text-[8px] font-bold text-white/40 uppercase mt-1">Starting Camera...</p>
                                </div>
                            </div>
                            <div x-show="cameraError" class="absolute inset-0 flex items-center justify-center bg-black/80">
                                <div class="text-center">
                                    <span class="material-symbols-outlined text-3xl text-error/60">videocam_off</span>
                                    <p class="text-[8px] font-bold text-error/60 uppercase mt-1">Camera Unavailable</p>
                                </div>
                            </div>
                            {{-- Flash effect --}}
                            <div x-ref="flashOverlay" class="absolute inset-0 bg-white pointer-events-none" style="opacity:0; transition: opacity 0.15s;"></div>
                        </div>
                        <div class="flex items-center gap-1.5" x-show="requirePhoto">
                            <div class="w-2 h-2 rounded-full" :class="cameraReady ? 'bg-success animate-pulse' : (cameraError ? 'bg-error' : 'bg-warning animate-pulse')"></div>
                            <span class="text-[8px] font-black uppercase tracking-widest opacity-50" x-text="cameraReady ? 'Camera Live' : (cameraError ? 'Camera Off' : 'Connecting...')"></span>
                        </div>
                    </div>

                    {{-- Status + Actions --}}
                    <div class="flex flex-col items-center gap-6">
                    {{-- Status --}}
                    @if(!$todayLog)
                        {{-- Not clocked in --}}
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-20 h-20 rounded-full bg-success/10 border-4 border-success/20 flex items-center justify-center animate-pulse">
                                <span class="material-symbols-outlined text-4xl text-success">login</span>
                            </div>
                            <h4 class="text-sm font-black text-on-surface uppercase tracking-widest">Ready to Clock In</h4>
                            <p class="text-[10px] font-bold text-on-surface-variant opacity-50 text-center max-w-xs">
                                Photo, location, IP & device auto-captured
                            </p>
                        </div>
                        <form action="{{ route('attendance.clock-in') }}" method="POST" id="clockInForm" @submit.prevent="captureAndSubmit($event)">
                            @csrf
                            <input type="hidden" name="latitude" x-model="latitude">
                            <input type="hidden" name="longitude" x-model="longitude">
                            <input type="hidden" name="device_info" x-model="deviceInfo">
                            <input type="hidden" name="photo" x-model="photoData">
                            <button type="submit" class="btn btn-success btn-lg rounded-2xl font-black text-sm uppercase tracking-widest gap-3 px-12 shadow-lg shadow-success/30 hover:shadow-xl hover:shadow-success/40 transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="(requireLocation && !locationReady) || (requirePhoto && !cameraReady)">
                                <span class="material-symbols-outlined text-xl" x-show="(!requireLocation || locationReady) && (!requirePhoto || cameraReady)">fingerprint</span>
                                <span class="loading loading-spinner loading-xs" x-show="(requireLocation && !locationReady) || (requirePhoto && !cameraReady)"></span>
                                <span x-text="((requireLocation && !locationReady) || (requirePhoto && !cameraReady)) ? 'Access Required' : 'Clock In'"></span>
                            </button>
                        </form>
                        {{-- Permission Warnings --}}
                        <div class="flex flex-col items-center gap-2 mt-2" x-show="(requireLocation && !locationReady) || (requirePhoto && !cameraReady)">
                            <p class="text-[9px] font-bold text-error uppercase tracking-tighter flex items-center gap-1" x-show="requireLocation && locationError">
                                <span class="material-symbols-outlined text-xs">location_off</span> Location Access Denied
                            </p>
                            <p class="text-[9px] font-bold text-error uppercase tracking-tighter flex items-center gap-1" x-show="requirePhoto && cameraError">
                                <span class="material-symbols-outlined text-xs">videocam_off</span> Camera Access Denied
                            </p>
                            <p class="text-[8px] font-medium text-on-surface-variant opacity-60 text-center max-w-[200px]">
                                Attendance cannot be marked without <span x-show="requireLocation">location</span><span x-show="requireLocation && requirePhoto"> and </span><span x-show="requirePhoto">photo</span> capture.
                            </p>
                            <button @click="retryAccess()" class="btn btn-ghost btn-xs text-[8px] font-black uppercase tracking-widest text-primary">
                                Retry Permissions
                            </button>
                        </div>

                    @elseif($todayLog && !$todayLog->check_out)
                        {{-- Clocked in, waiting for clock out --}}
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-24 h-24 rounded-full bg-warning/10 border-4 border-warning/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-5xl text-warning">schedule</span>
                            </div>
                            <h4 class="text-sm font-black text-on-surface uppercase tracking-widest">Currently Working</h4>
                            <div class="flex items-center gap-6 bg-surface-container-low/50 px-6 py-3 rounded-xl border border-outline-variant/10">
                                <div class="text-center">
                                    <span class="text-[8px] font-black uppercase tracking-widest opacity-40 block">Clocked In</span>
                                    <span class="text-lg font-black text-success">{{ \Carbon\Carbon::parse($todayLog->check_in)->format('h:i A') }}</span>
                                </div>
                                <div class="w-px h-8 bg-outline-variant/10"></div>
                                <div class="text-center">
                                    <span class="text-[8px] font-black uppercase tracking-widest opacity-40 block">Duration</span>
                                    <span class="text-lg font-black text-primary" id="duration-counter">--:--</span>
                                </div>
                                @if($todayLog->is_late)
                                    <div class="w-px h-8 bg-outline-variant/10"></div>
                                    <div class="text-center">
                                        <span class="text-[8px] font-black uppercase tracking-widest opacity-40 block">Late</span>
                                        <span class="text-lg font-black text-error">{{ $todayLog->late_minutes }}m</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('attendance.clock-out') }}" method="POST" id="clockOutForm" @submit.prevent="captureAndSubmit($event)">
                            @csrf
                            <input type="hidden" name="latitude" x-model="latitude">
                            <input type="hidden" name="longitude" x-model="longitude">
                            <input type="hidden" name="device_info" x-model="deviceInfo">
                            <input type="hidden" name="photo" x-model="photoData">
                            <button type="submit" class="btn btn-error btn-lg rounded-2xl font-black text-sm uppercase tracking-widest gap-3 px-12 shadow-lg shadow-error/30 hover:shadow-xl hover:shadow-error/40 transition-all duration-300 hover:scale-105 text-white disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="(requireLocation && !locationReady) || (requirePhoto && !cameraReady)">
                                <span class="material-symbols-outlined text-xl" x-show="(!requireLocation || locationReady) && (!requirePhoto || cameraReady)">logout</span>
                                <span class="loading loading-spinner loading-xs" x-show="(requireLocation && !locationReady) || (requirePhoto && !cameraReady)"></span>
                                <span x-text="((requireLocation && !locationReady) || (requirePhoto && !cameraReady)) ? 'Access Required' : 'Clock Out'"></span>
                            </button>
                        </form>
                        {{-- Permission Warnings --}}
                        <div class="flex flex-col items-center gap-2 mt-2" x-show="(requireLocation && !locationReady) || (requirePhoto && !cameraReady)">
                            <p class="text-[9px] font-bold text-error uppercase tracking-tighter flex items-center gap-1" x-show="requireLocation && locationError">
                                <span class="material-symbols-outlined text-xs">location_off</span> Location Access Denied
                            </p>
                            <p class="text-[9px] font-bold text-error uppercase tracking-tighter flex items-center gap-1" x-show="requirePhoto && cameraError">
                                <span class="material-symbols-outlined text-xs">videocam_off</span> Camera Access Denied
                            </p>
                            <button @click="retryAccess()" class="btn btn-ghost btn-xs text-[8px] font-black uppercase tracking-widest text-primary">
                                Retry Permissions
                            </button>
                        </div>

                    @else
                        {{-- Already completed for the day --}}
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-24 h-24 rounded-full bg-primary/10 border-4 border-primary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-5xl text-primary">task_alt</span>
                            </div>
                            <h4 class="text-sm font-black text-on-surface uppercase tracking-widest">Day Completed</h4>
                            <div class="flex items-center gap-6 bg-surface-container-low/50 px-6 py-3 rounded-xl border border-outline-variant/10">
                                <div class="text-center">
                                    <span class="text-[8px] font-black uppercase tracking-widest opacity-40 block">In</span>
                                    <span class="text-lg font-black text-success">{{ \Carbon\Carbon::parse($todayLog->check_in)->format('h:i A') }}</span>
                                </div>
                                <span class="material-symbols-outlined text-on-surface-variant/20">arrow_forward</span>
                                <div class="text-center">
                                    <span class="text-[8px] font-black uppercase tracking-widest opacity-40 block">Out</span>
                                    <span class="text-lg font-black text-error">{{ \Carbon\Carbon::parse($todayLog->check_out)->format('h:i A') }}</span>
                                </div>
                                <div class="w-px h-8 bg-outline-variant/10"></div>
                                <div class="text-center">
                                    <span class="text-[8px] font-black uppercase tracking-widest opacity-40 block">Worked</span>
                                    <span class="text-lg font-black text-primary">{{ $todayLog->worked_hours }}h</span>
                                </div>
                                @if($todayLog->overtime_minutes > 0)
                                    <div class="w-px h-8 bg-outline-variant/10"></div>
                                    <div class="text-center">
                                        <span class="text-[8px] font-black uppercase tracking-widest opacity-40 block">OT</span>
                                        <span class="text-lg font-black text-secondary">{{ $todayLog->overtime_minutes }}m</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Device Info & Location Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Location Card --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                    <h4 class="text-[10px] font-black uppercase tracking-widest opacity-80">Location</h4>
                    <span class="ml-auto badge badge-xs text-[7px] font-black px-2 h-auto uppercase"
                          :class="locationReady ? 'badge-success text-white' : 'badge-warning'"
                          x-text="locationReady ? 'Acquired' : 'Pending'"></span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">Latitude</span>
                        <span class="text-[10px] font-black text-on-surface font-mono" x-text="latitude || '—'"></span>
                    </div>
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">Longitude</span>
                        <span class="text-[10px] font-black text-on-surface font-mono" x-text="longitude || '—'"></span>
                    </div>
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">Accuracy</span>
                        <span class="text-[10px] font-black text-on-surface font-mono" x-text="accuracy ? accuracy + 'm' : '—'"></span>
                    </div>
                </div>
            </div>

            {{-- Network Info --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-sm text-secondary">wifi</span>
                    <h4 class="text-[10px] font-black uppercase tracking-widest opacity-80">Network</h4>
                    <span class="ml-auto badge badge-success badge-xs text-[7px] font-black px-2 h-auto text-white uppercase">Active</span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">IP Address</span>
                        <span class="text-[10px] font-black text-on-surface font-mono">{{ request()->ip() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">Protocol</span>
                        <span class="text-[10px] font-black text-on-surface font-mono uppercase">{{ request()->secure() ? 'HTTPS' : 'HTTP' }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">User Agent</span>
                        <span class="text-[10px] font-black text-on-surface font-mono truncate max-w-[180px]" x-text="browserName"></span>
                    </div>
                </div>
            </div>

            {{-- Device Info --}}
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-sm text-accent">devices</span>
                    <h4 class="text-[10px] font-black uppercase tracking-widest opacity-80">Device</h4>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">Platform</span>
                        <span class="text-[10px] font-black text-on-surface font-mono" x-text="platform"></span>
                    </div>
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">Screen</span>
                        <span class="text-[10px] font-black text-on-surface font-mono" x-text="screenRes"></span>
                    </div>
                    <div class="flex justify-between items-center bg-surface-container-low px-3 py-2 rounded-lg">
                        <span class="text-[9px] font-bold opacity-40 uppercase">Timezone</span>
                        <span class="text-[10px] font-black text-on-surface font-mono" x-text="timezone"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Attendance History --}}
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant/10 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-outline-variant/5 bg-surface-container-low/20 flex items-center justify-between">
                <h3 class="font-bold text-[10px] uppercase tracking-wider text-on-surface opacity-80 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">history</span> Recent Attendance
                </h3>
                <a href="{{ route('attendance.index') }}" class="text-[9px] font-black text-primary uppercase tracking-widest hover:underline">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-xs w-full">
                    <thead>
                        <tr class="bg-surface-container-low/30">
                            <th class="text-[8px] font-black uppercase tracking-widest opacity-60">Date</th>
                            <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">In</th>
                            <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Out</th>
                            <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Hours</th>
                            <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">Status</th>
                            <th class="text-[8px] font-black uppercase tracking-widest opacity-60 text-center">OT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs as $log)
                            <tr class="hover:bg-surface-container-low/20 transition-colors">
                                <td class="text-[10px] font-bold text-on-surface">{{ $log->date->format('D, d M') }}</td>
                                <td class="text-center text-[10px] font-bold text-success">{{ $log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('h:i A') : '—' }}</td>
                                <td class="text-center text-[10px] font-bold text-error">{{ $log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('h:i A') : '—' }}</td>
                                <td class="text-center text-[10px] font-black text-on-surface">{{ number_format($log->worked_hours ?? 0, 1) }}h</td>
                                <td class="text-center">
                                    <span class="badge badge-xs text-[7px] font-black px-2 py-0.5 h-auto uppercase
                                        {{ $log->status === 'present' ? 'badge-success text-white' : ($log->status === 'late' ? 'badge-warning' : 'badge-error text-white') }}">
                                        {{ $log->status }}{{ $log->is_late ? ' (' . $log->late_minutes . 'm)' : '' }}
                                    </span>
                                </td>
                                <td class="text-center text-[10px] font-bold text-secondary {{ ($log->overtime_minutes ?? 0) > 0 ? '' : 'opacity-30' }}">{{ $log->overtime_minutes ?? 0 }}m</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-[10px] font-bold opacity-40 italic uppercase">No recent attendance records</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

    {{-- JavaScript for Kiosk --}}
    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            const h = now.getHours().toString().padStart(2, '0');
            const m = now.getMinutes().toString().padStart(2, '0');
            const s = now.getSeconds().toString().padStart(2, '0');
            const time12 = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            
            const mainClock = document.getElementById('main-clock');
            const liveClock = document.getElementById('live-clock');
            if (mainClock) mainClock.textContent = time12;
            if (liveClock) liveClock.textContent = time12;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Duration counter for active session
        @if($todayLog && !$todayLog->check_out)
        (function() {
            const checkInTime = new Date('{{ \Carbon\Carbon::parse($todayLog->check_in)->toISOString() }}');
            function updateDuration() {
                const now = new Date();
                const diff = Math.floor((now - checkInTime) / 1000);
                const hours = Math.floor(diff / 3600);
                const mins = Math.floor((diff % 3600) / 60);
                const secs = diff % 60;
                const el = document.getElementById('duration-counter');
                if (el) el.textContent = hours.toString().padStart(2,'0') + ':' + mins.toString().padStart(2,'0') + ':' + secs.toString().padStart(2,'0');
            }
            setInterval(updateDuration, 1000);
            updateDuration();
        })();
        @endif

        // Alpine.js Kiosk App
        function kioskApp(config) {
            return {
                isKioskEnabled: config.isKioskEnabled,
                requirePhoto: config.requirePhoto,
                requireLocation: config.requireLocation,
                latitude: '',
                longitude: '',
                accuracy: '',
                deviceInfo: '',
                photoData: '',
                locationReady: false,
                locationError: false,
                cameraReady: false,
                cameraError: false,
                photoCaptured: false,
                browserName: '',
                platform: '',
                screenRes: '',
                timezone: '',
                stream: null,

                init() {
                    // Device info
                    this.platform = navigator.platform || 'Unknown';
                    this.screenRes = screen.width + 'x' + screen.height;
                    this.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    
                    // Browser detection
                    const ua = navigator.userAgent;
                    if (ua.includes('Chrome') && !ua.includes('Edg')) this.browserName = 'Chrome';
                    else if (ua.includes('Firefox')) this.browserName = 'Firefox';
                    else if (ua.includes('Safari') && !ua.includes('Chrome')) this.browserName = 'Safari';
                    else if (ua.includes('Edg')) this.browserName = 'Edge';
                    else this.browserName = 'Browser';

                    // Compose device info string
                    this.deviceInfo = this.browserName + ' | ' + this.platform + ' | ' + this.screenRes + ' | ' + this.timezone;

                    // Request Geolocation
                    this.requestLocation();

                    // Start Camera
                    this.startCamera();
                },

                async startCamera() {
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: { width: 480, height: 480, facingMode: 'user' },
                            audio: false
                        });
                        this.$refs.cameraVideo.srcObject = this.stream;
                        this.cameraReady = true;
                    } catch (err) {
                        console.warn('Camera error:', err.message);
                        this.cameraError = true;
                    }
                },

                capturePhoto() {
                    if (!this.cameraReady) return '';

                    const video = this.$refs.cameraVideo;
                    const canvas = this.$refs.cameraCanvas;
                    canvas.width = 480;
                    canvas.height = 480;
                    const ctx = canvas.getContext('2d');
                    
                    // Mirror the image to match the preview
                    ctx.translate(480, 0);
                    ctx.scale(-1, 1);
                    ctx.drawImage(video, 0, 0, 480, 480);

                    // Flash effect
                    const flash = this.$refs.flashOverlay;
                    if (flash) {
                        flash.style.opacity = '1';
                        setTimeout(() => { flash.style.opacity = '0'; }, 150);
                    }

                    // Show captured photo
                    const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                    this.$refs.capturedPhoto.src = dataUrl;
                    this.photoCaptured = true;

                    return dataUrl;
                },

                captureAndSubmit(event) {
                    // Capture photo
                    this.photoData = this.capturePhoto();
                    // Refresh device info
                    this.deviceInfo = this.browserName + ' | ' + this.platform + ' | ' + this.screenRes + ' | ' + this.timezone;

                    // Stop camera
                    if (this.stream) {
                        this.stream.getTracks().forEach(t => t.stop());
                    }

                    // Small delay for flash effect, then submit
                    setTimeout(() => {
                        event.target.submit();
                    }, 300);
                },

                requestLocation() {
                    this.locationReady = false;
                    this.locationError = false;
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.latitude = position.coords.latitude.toFixed(7);
                                this.longitude = position.coords.longitude.toFixed(7);
                                this.accuracy = Math.round(position.coords.accuracy);
                                this.locationReady = true;
                                this.locationError = false;
                            },
                            (error) => {
                                console.warn('Geolocation error:', error.message);
                                this.locationReady = false;
                                this.locationError = true;
                                this.latitude = '';
                                this.longitude = '';
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        this.locationError = true;
                        this.locationReady = false;
                    }
                },

                retryAccess() {
                    this.requestLocation();
                    this.startCamera();
                }
            }
        }
    </script>
</x-app-layout>
