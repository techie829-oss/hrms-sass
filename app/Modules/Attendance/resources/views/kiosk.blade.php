<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-2.5 shadow-sm flex items-center justify-center">
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
        <div x-show="!isKioskEnabled" class="bg-white rounded-2xl border border-slate-200 p-10 text-center shadow-sm" x-cloak>
            <span class="material-symbols-outlined text-5xl text-rose-500 mb-4">block</span>
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">Kiosk Access Disabled</h2>
            <p class="text-xs font-semibold text-slate-400 mt-2">
                The attendance kiosk has been disabled by the administrator for this policy.
            </p>
            <a href="{{ route('attendance.index') }}" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl mt-6 px-8 shadow-sm text-xs font-semibold inline-block">
                Go to Attendance Logs
            </a>
        </div>

        <div x-show="isKioskEnabled" class="space-y-6">

        {{-- Main Kiosk Card --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/10 shadow-xl overflow-hidden">
            {{-- Header Strip --}}
            <div class="bg-gradient-to-r from-primary/10 via-secondary/5 to-accent/10 p-6 border-b border-slate-100">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-center overflow-hidden">
                        @if($user->employee?->profile_photo)
                            <img src="{{ asset('storage/' . $user->employee->profile_photo) }}" class="w-full h-full object-cover" />
                        @else
                            <span class="material-symbols-outlined text-3xl text-slate-400">person</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $user->name }}</h3>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mt-0.5">
                            {{ $employee?->designation?->name ?? 'Employee' }} · {{ $employee?->department?->name ?? 'General' }}
                        </p>
                        <p class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider mt-1">
                            ID: {{ $employee?->employee_id ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="ml-auto text-right">
                        <div class="text-3xl font-extrabold text-slate-800" id="main-clock">--:--</div>
                        <div class="text-[9px] font-semibold text-slate-400 uppercase tracking-wider">{{ now()->format('l, d M Y') }}</div>
                        @if($shift)
                            <div class="mt-1 px-2 py-0.5 rounded-full bg-primary/10 border border-primary/20 text-[8px] font-bold text-primary uppercase inline-block">
                                {{ $shift->name }} · {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Clock In/Out Section --}}
            <div class="p-8 relative min-h-[350px]">
                <div class="flex flex-col lg:flex-row items-center gap-8 justify-center h-full">
                    
                    {{-- Camera Preview --}}
                    <div class="flex flex-col items-center gap-3">
                        <div class="relative w-48 h-48 rounded-2xl overflow-hidden bg-black/90 border-2 border-outline-variant/20 shadow-xl">
                            <video x-ref="cameraVideo" autoplay playsinline muted class="w-full h-full object-cover" :style="isSecureContext ? 'transform: scaleX(-1);' : 'display:none;'"></video>
                            <canvas x-ref="cameraCanvas" class="hidden"></canvas>
                            <img x-ref="capturedPhoto" x-show="photoCaptured" class="absolute inset-0 w-full h-full object-cover" style="transform: scaleX(-1); display:none;" />
                            
                            <template x-if="!isSecureContext">
                                <div class="absolute inset-0 flex flex-col items-center justify-center bg-surface-container-low p-4 text-center">
                                    <span class="material-symbols-outlined text-4xl text-warning mb-2">lock_open</span>
                                    <p class="text-[9px] font-black uppercase text-on-surface opacity-60">Insecure Connection</p>
                                    <p class="text-[8px] font-medium text-on-surface-variant opacity-40 mt-1 uppercase">Camera & Location disabled</p>
                                </div>
                            </template>

                            <template x-if="isSecureContext">
                                <div>
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
                                </div>
                            </template>
                            <div x-ref="flashOverlay" class="absolute inset-0 bg-white pointer-events-none" style="opacity:0; transition: opacity 0.15s;"></div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full" :class="!isSecureContext ? 'bg-warning' : (cameraReady ? 'bg-success animate-pulse' : (cameraError ? 'bg-error' : 'bg-warning animate-pulse'))"></div>
                            <span class="text-[8px] font-black uppercase tracking-widest opacity-50" x-text="!isSecureContext ? 'HTTP Mode' : (cameraReady ? 'Camera Live' : (cameraError ? 'Camera Off' : 'Connecting...'))"></span>
                        </div>
                    </div>

                    {{-- Actions Container --}}
                    <div class="flex flex-col items-center gap-6 min-w-[300px] relative">
                        
                        @if(!$todayLog || ($isMultiEnabled && $todayLog->check_out))
                            {{-- Ready to Clock In --}}
                            <div class="flex flex-col items-center gap-4 text-center">
                                <div class="w-20 h-20 rounded-full bg-emerald-50 border-4 border-emerald-100 flex items-center justify-center animate-pulse">
                                    <span class="material-symbols-outlined text-4xl text-emerald-600">login</span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Ready to Clock In</h4>
                                
                                <form action="{{ route('attendance.clock-in') }}" method="POST" x-ref="clockInForm" @submit.prevent="captureAndSubmit($event)">
                                    @csrf
                                    <input type="hidden" name="latitude" x-model="latitude">
                                    <input type="hidden" name="longitude" x-model="longitude">
                                    <input type="hidden" name="device_info" x-model="deviceInfo">
                                    <input type="hidden" name="photo" x-model="photoData">
                                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold text-sm uppercase tracking-wider gap-3 px-12 py-4 shadow-md shadow-emerald-500/20 transition-all duration-300 hover:scale-105 flex items-center justify-center"
                                        :disabled="isSecureContext && ((requireLocation && !locationReady) || (requirePhoto && !cameraReady))">
                                        <span class="material-symbols-outlined text-2xl" x-show="!isSecureContext || ((!requireLocation || locationReady) && (!requirePhoto || cameraReady))">fingerprint</span>
                                        <span class="loading loading-spinner loading-xs" x-show="isSecureContext && (((requireLocation && !locationReady) && !locationError) || ((requirePhoto && !cameraReady) && !cameraError))"></span>
                                        <span x-text="!isSecureContext ? 'Clock In (HTTP)' : ((requireLocation && !locationReady) || (requirePhoto && !cameraReady) ? 'Access Required' : 'Clock In Now')"></span>
                                    </button>
                                </form>
                            </div>

                        @elseif($todayLog && !$todayLog->check_out)
                            {{-- Currently Working --}}
                            <div class="flex flex-col items-center gap-4 text-center">
                                <div class="w-20 h-20 rounded-full bg-amber-50 border-4 border-amber-100 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-amber-500">schedule</span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Currently Working</h4>
                                
                                <button @click="showConfirmOut = true" class="w-full bg-rose-500 hover:bg-rose-600 text-white rounded-2xl font-bold text-sm uppercase tracking-wider gap-3 px-12 py-4 shadow-md shadow-rose-500/20 transition-all duration-300 hover:scale-105 flex items-center justify-center"
                                    :disabled="isSecureContext && ((requireLocation && !locationReady) || (requirePhoto && !cameraReady))">
                                    <span class="material-symbols-outlined text-xl" x-show="!isSecureContext || ((!requireLocation || locationReady) && (!requirePhoto || cameraReady))">logout</span>
                                    <span x-text="!isSecureContext ? 'Clock Out (HTTP)' : ((requireLocation && !locationReady) || (requirePhoto && !cameraReady) ? 'Access Required' : 'Clock Out Now')"></span>
                                </button>

                                <form action="{{ route('attendance.clock-out') }}" method="POST" x-ref="clockOutForm" class="hidden">
                                    @csrf
                                    <input type="hidden" name="latitude" x-model="latitude">
                                    <input type="hidden" name="longitude" x-model="longitude">
                                    <input type="hidden" name="device_info" x-model="deviceInfo">
                                    <input type="hidden" name="photo" x-model="photoData">
                                </form>
                            </div>

                        @else
                            {{-- Day Completed --}}
                            <div class="flex flex-col items-center gap-4 text-center">
                                <div class="w-20 h-20 rounded-full bg-primary/5 border-4 border-primary/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-primary">task_alt</span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Day Completed</h4>
                                <div class="px-4 py-1.5 rounded-xl bg-primary/10 border border-primary/20 text-xs font-bold text-primary uppercase">Total Worked: {{ $todayLog->worked_hours }}h</div>
                            </div>
                        @endif

                        {{-- Secure Context Permission Overlay --}}
                        <template x-if="isSecureContext">
                            <div x-show="(requireLocation && locationError) || (requirePhoto && cameraError)" 
                                 class="absolute inset-0 bg-white/95 backdrop-blur-sm z-30 flex flex-col items-center justify-center p-6 text-center"
                                 x-transition x-cloak>
                                <span class="material-symbols-outlined text-3xl text-rose-500 mb-2">security_update_warning</span>
                                <h3 class="text-xs font-bold uppercase tracking-wider mb-1">Access Required</h3>
                                <p class="text-[9px] font-semibold text-slate-400 mb-4 max-w-[200px]">Camera & Location permissions are required.</p>
                                <button @click="retryAccess()" class="btn btn-primary btn-sm w-full rounded-xl font-bold uppercase tracking-wider text-[10px] h-9">Try Again</button>
                                
                                @if(config('app.debug'))
                                    <button @click="latitude='26.8467'; longitude='80.9462'; photoData='debug_bypass'; $nextTick(() => { if($refs.clockInForm) $refs.clockInForm.submit(); if($refs.clockOutForm) $refs.clockOutForm.submit(); })" 
                                        class="btn btn-ghost btn-xs text-[8px] font-bold uppercase text-amber-500 mt-4 hover:bg-amber-50/5">Skip & Continue (Debug)</button>
                                @endif
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Device Info & Location Cards --}}
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[280px] bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Location</h4>
                    <span class="ml-auto px-2 py-0.5 rounded-full text-[8px] font-bold border"
                          :class="locationReady ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (locationError ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200')"
                          x-text="locationReady ? 'Acquired' : (locationError ? 'Denied' : 'Insecure Connection')"></span>
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
                </div>
            </div>

            <div class="flex-1 min-w-[280px] bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-sm text-slate-500">wifi</span>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Network</h4>
                    <span class="ml-auto px-2 py-0.5 rounded-full text-[8px] font-bold border bg-emerald-50 text-emerald-700 border-emerald-200">Active</span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center bg-slate-50 px-3 py-2 rounded-xl border border-slate-200/40">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">IP Address</span>
                        <span class="text-[10px] font-bold text-slate-700 font-mono">{{ request()->ip() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50 px-3 py-2 rounded-xl border border-slate-200/40">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Protocol</span>
                        <span class="text-[10px] font-bold text-slate-700 font-mono uppercase">{{ request()->secure() ? 'HTTPS' : 'HTTP' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex-1 min-w-[280px] bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-sm text-slate-500">devices</span>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Device</h4>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center bg-slate-50 px-3 py-2 rounded-xl border border-slate-200/40">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Platform</span>
                        <span class="text-[10px] font-bold text-slate-700 font-mono truncate max-w-[150px]" x-text="platform"></span>
                    </div>
                    <div class="flex justify-between items-center bg-slate-50 px-3 py-2 rounded-xl border border-slate-200/40">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">Browser</span>
                        <span class="text-[10px] font-bold text-slate-700 font-mono" x-text="browserName"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Attendance History --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <h3 class="font-bold text-[10px] uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">history</span> Recent Attendance
                </h3>
                <a href="{{ route('attendance.index') }}" class="text-[9px] font-bold text-primary uppercase tracking-wider hover:underline">View All →</a>
            </div>
            <div class="table-crm">
                <x-table :headers="['Date', 'In', 'Out', 'Hours', 'Status', 'OT']" :striped="false">
                    @forelse($recentLogs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors border-b border-slate-100 last:border-0">
                            <td class="text-[11px] font-bold text-slate-700">{{ $log->date->format('D, d M') }}</td>
                            <td class="text-center text-[11px] font-bold text-emerald-600">{{ $log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('h:i A') : '—' }}</td>
                            <td class="text-center text-[11px] font-bold text-rose-500">{{ $log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('h:i A') : '—' }}</td>
                            <td class="text-center text-[11px] font-bold text-slate-700">{{ number_format($log->worked_hours ?? 0, 1) }}h</td>
                            <td class="text-center font-bold">
                                @php
                                    $statColor = $log->status === 'present' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($log->status === 'late' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200');
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[8px] font-bold border uppercase {{ $statColor }}">
                                    {{ $log->status }}{{ $log->is_late ? ' (' . $log->late_minutes . 'm)' : '' }}
                                </span>
                            </td>
                            <td class="text-center text-[11px] font-bold text-slate-500 {{ ($log->overtime_minutes ?? 0) > 0 ? '' : 'opacity-35' }}">{{ $log->overtime_minutes ?? 0 }}m</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-[10px] font-semibold text-slate-400 italic uppercase">No recent attendance records</td>
                        </tr>
                    @endforelse
                </x-table>
            </div>
        </div>
        </div>

        {{-- Clock Out Confirmation Modal --}}
        <div x-show="showConfirmOut" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition x-cloak>
            <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 overflow-hidden" @click.away="showConfirmOut = false">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-rose-50 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                        <span class="material-symbols-outlined text-3xl text-rose-500">logout</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-700 uppercase tracking-wider mb-2">Confirm Clock Out</h3>
                    <p class="text-xs font-semibold text-slate-400 leading-relaxed mb-6">
                        Are you sure you want to end your session? This will record your final attendance for today.
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showConfirmOut = false" class="btn btn-sm border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 rounded-xl px-4 py-2 h-auto text-xs font-semibold select-none">
                            Cancel
                        </button>
                        <button @click="confirmClockOut()" class="btn btn-sm bg-rose-500 hover:bg-rose-600 text-white border-none rounded-xl px-4 py-2 h-auto text-xs font-semibold shadow-sm select-none">
                            Yes, Clock Out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const time12 = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const mainClock = document.getElementById('main-clock');
            const liveClock = document.getElementById('live-clock');
            if (mainClock) mainClock.textContent = time12;
            if (liveClock) liveClock.textContent = time12;
        }
        setInterval(updateClock, 1000);
        updateClock();

        function kioskApp(config) {
            return {
                isKioskEnabled: config.isKioskEnabled,
                requirePhoto: config.requirePhoto,
                requireLocation: config.requireLocation,
                isSecureContext: window.isSecureContext,
                showConfirmOut: false,
                latitude: '',
                longitude: '',
                deviceInfo: '',
                photoData: '',
                locationReady: false,
                locationError: false,
                cameraReady: false,
                cameraError: false,
                photoCaptured: false,
                browserName: '',
                platform: '',
                stream: null,

                init() {
                    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                        this.isSecureContext = true;
                    }

                    this.platform = navigator.platform || 'Unknown';
                    const ua = navigator.userAgent;
                    if (ua.includes('Chrome') && !ua.includes('Edg')) this.browserName = 'Chrome';
                    else if (ua.includes('Firefox')) this.browserName = 'Firefox';
                    else if (ua.includes('Safari') && !ua.includes('Chrome')) this.browserName = 'Safari';
                    else if (ua.includes('Edg')) this.browserName = 'Edge';
                    else this.browserName = 'Browser';

                    this.deviceInfo = this.browserName + ' | ' + this.platform;
                    
                    if (this.isSecureContext) {
                        this.requestLocation();
                        this.startCamera();
                    }
                },

                async startCamera() {
                    if (!this.requirePhoto || !this.isSecureContext) return;
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'user', width: { ideal: 480 }, height: { ideal: 480 } },
                            audio: false
                        });
                        this.$refs.cameraVideo.srcObject = this.stream;
                        this.cameraReady = true;
                    } catch (err) {
                        this.cameraError = true;
                    }
                },

                capturePhoto() {
                    if (!this.cameraReady) return '';
                    const canvas = this.$refs.cameraCanvas;
                    canvas.width = 480;
                    canvas.height = 480;
                    const ctx = canvas.getContext('2d');
                    ctx.translate(480, 0);
                    ctx.scale(-1, 1);
                    ctx.drawImage(this.$refs.cameraVideo, 0, 0, 480, 480);
                    this.photoCaptured = true;
                    return canvas.toDataURL('image/jpeg', 0.8);
                },

                confirmClockOut() {
                    this.showConfirmOut = false;
                    const form = this.$refs.clockOutForm;
                    if (this.isSecureContext) {
                        this.photoData = this.capturePhoto();
                    } else {
                        this.photoData = 'insecure_context_bypass_out';
                    }
                    if (this.stream) this.stream.getTracks().forEach(t => t.stop());
                    setTimeout(() => { form.submit(); }, 300);
                },

                captureAndSubmit(event) {
                    if (this.isSecureContext) {
                        this.photoData = this.capturePhoto();
                    } else {
                        this.photoData = 'insecure_context_bypass';
                    }
                    if (this.stream) this.stream.getTracks().forEach(t => t.stop());
                    setTimeout(() => { event.target.submit(); }, 300);
                },

                requestLocation() {
                    if (!this.isSecureContext) return;
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (p) => {
                                this.latitude = p.coords.latitude.toFixed(7);
                                this.longitude = p.coords.longitude.toFixed(7);
                                this.locationReady = true;
                                this.locationError = false;
                            },
                            () => {
                                this.locationError = true;
                            },
                            { enableHighAccuracy: true, timeout: 5000 }
                        );
                    } else {
                        this.locationError = true;
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
