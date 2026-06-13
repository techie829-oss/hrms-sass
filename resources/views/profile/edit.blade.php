<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-slate-900 leading-tight">
            {{ __('Account & Professional Settings') }}
        </h2>
    </x-slot>

    <div class="space-y-6 max-w-7xl mx-auto py-6">
        {{-- Profile Header Card (Premium Aesthetic) --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8 group/header transition-all duration-500 hover:shadow-md">
            {{-- Cover Photo Section --}}
            <div class="h-44 relative group/cover bg-primary-50">
                {{-- Image Wrapper with overflow-hidden to allow zoom but not clip profile info --}}
                <div class="absolute inset-0 overflow-hidden">
                    @if($user->employee?->cover_photo)
                        <img src="{{ asset('storage/' . $user->employee->cover_photo) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/cover:scale-105" />
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-primary-50 via-slate-50 to-primary-100"></div>
                    @endif
                </div>
                
                {{-- Cover Upload Overlay --}}
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/cover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" onclick="document.getElementById('cover_input').click()">
                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-3 border border-white/30 flex flex-col items-center gap-1 group-hover/cover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-white text-2xl">photo_camera</span>
                        <span class="text-[10px] font-bold text-white uppercase tracking-widest">Update Banner</span>
                    </div>
                </div>

                <form id="cover_upload_form" action="{{ url('/profile/cover') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="cover_input" name="cover_photo" onchange="document.getElementById('cover_upload_form').submit()" accept="image/*">
                </form>

                <div class="absolute -bottom-16 left-8 flex items-end gap-6 z-10">
                    {{-- Profile Photo Section --}}
                    <div class="w-28 h-28 rounded-2xl bg-white border-4 border-white shadow-lg flex items-center justify-center relative overflow-hidden group/avatar">
                        @if($user->employee?->profile_photo)
                            <img src="{{ asset('storage/' . $user->employee->profile_photo) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-primary-50 flex items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-primary-500">person</span>
                            </div>
                        @endif

                        {{-- Avatar Upload Overlay --}}
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover/avatar:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" onclick="document.getElementById('photo_input').click()">
                            <span class="material-symbols-outlined text-white text-2xl group-hover/avatar:scale-110 transition-transform">add_a_photo</span>
                        </div>

                        <form id="photo_upload_form" action="{{ url('/profile/photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <input type="file" id="photo_input" name="profile_photo" onchange="document.getElementById('photo_upload_form').submit()" accept="image/*">
                        </form>
                    </div>
                    <div class="pb-1">
                        <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-2 mt-1">
                            {{ $user->employee?->designation?->name ?? 'User' }} 
                            <span class="opacity-30 self-center">|</span>
                            {{ $user->employee?->department?->name ?? 'General' }}
                        </p>
                    </div>
                </div>

                {{-- Media Upload Success Badge --}}
                @if (session('status') === 'photo-updated' || session('status') === 'cover-updated' || session('status') === 'main-image-updated')
                    <div class="absolute top-4 right-4 z-50" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                        <div class="bg-green-600 text-white text-xs font-bold uppercase px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            Media Updated Successfully
                        </div>
                    </div>
                @endif
            </div>
            <div class="pt-20 pb-6 px-8 flex flex-wrap gap-8 items-center bg-slate-50 border-t border-slate-100">
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Employee ID</span>
                    <span class="text-sm font-bold text-slate-900">{{ $user->employee?->employee_id ?? 'N/A' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Joining Date</span>
                    <span class="text-sm font-bold text-slate-900">{{ $user->employee?->date_of_joining?->format('d M, Y') ?? 'N/A' }}</span>
                </div>
                @if($user->employee && $user->employee->date_of_joining)
                <div class="flex flex-col">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tenure</span>
                    <span class="text-sm font-bold text-primary-600">{{ floor($user->employee->date_of_joining->diffInMonths(now())) }} Months</span>
                </div>
                @endif
                <div class="ml-auto">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-900 uppercase tracking-wider">{{ $user->employee?->employment_type ?? 'Permanent' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Personal & Emergency Info (Editable) --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 md:p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Sidebar Professional Info (Read Only) & Actions --}}
            <div class="space-y-6">
                {{-- Work Details --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Employment Snapshot</h3>
                        <span class="text-[10px] font-bold text-primary-600 uppercase tracking-wider">Official Record</span>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Official Main Image --}}
                        <div class="flex flex-col items-center mb-6">
                            <div class="w-32 h-40 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden relative group/official shadow-sm">
                                @if($user->employee?->main_image)
                                    <img src="{{ asset('storage/' . $user->employee->main_image) }}" class="w-full h-full object-cover" />
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-primary-50">
                                        <span class="material-symbols-outlined text-4xl text-primary-500 mb-2">badge</span>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-center text-slate-400 px-2">No Official ID Photo</p>
                                    </div>
                                @endif

                                @if(auth()->user()->hasRole('superadmin') || auth()->user()->can('manage_employees'))
                                <div class="absolute inset-0 bg-slate-900/80 opacity-0 group-hover/official:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer gap-2" onclick="document.getElementById('main_image_input').click()">
                                    <span class="material-symbols-outlined text-white text-2xl">upload_file</span>
                                    <span class="text-[10px] font-bold text-white uppercase tracking-wider text-center px-4">Upload Official ID<br>(HR Only)</span>
                                </div>
                                <form id="main_image_upload_form" action="{{ route('tenant.profile.update-main-image') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $user->employee?->id }}">
                                    <input type="file" id="main_image_input" name="main_image" onchange="document.getElementById('main_image_upload_form').submit()" accept="image/*">
                                </form>
                                @endif
                            </div>
                            <p class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Official Identification Photo</p>
                        </div>

                        <div class="flex justify-between items-center bg-slate-50 px-4 py-3 rounded-lg border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Reporting To</span>
                            <span class="text-xs font-bold text-slate-800">{{ $user->employee?->reportingTo?->full_name ?? 'HR Department' }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 px-4 py-3 rounded-lg border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 uppercase tracking-wider">{{ $user->employee?->status ?? 'Active' }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 px-4 py-3 rounded-lg border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pay Frequency</span>
                            <span class="text-xs font-bold text-slate-800 uppercase">{{ $user->employee?->pay_frequency ?? 'Monthly' }}</span>
                        </div>
                    </div>
                    <div class="p-6 pt-0 text-xs text-slate-500 text-center leading-relaxed italic">
                        Notice: Professional details are managed by the HR administration. Please contact support to request data changes.
                    </div>
                </div>

                {{-- Danger Zone --}}
                <div class="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden">
                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
