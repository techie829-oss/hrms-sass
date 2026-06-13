<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manage Permissions</h2>
                <p class="text-sm text-slate-500 mt-1">Control granular global permissions available for assignment to roles.</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <span class="material-symbols-outlined text-sm mr-2">arrow_back</span> Back to Roles
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- 1. Left Column: Create Permission Form -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">New Permission</h3>
                    <p class="text-xs text-slate-500 font-medium">Add a new access key. Use lower case and hyphens for words (e.g., <code class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-mono text-[10px]">view_timesheet</code>).</p>
                </div>
                
                <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <x-input-label for="name" :value="__('Permission Name')" class="text-slate-700 font-semibold" />
                        <x-text-input id="name" name="name" type="text" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required placeholder="view_timesheet" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <x-input-label for="description" :value="__('Description / Usage')" class="text-slate-700 font-semibold" />
                        <textarea id="description" name="description" rows="3" 
                            class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm" 
                            placeholder="e.g. Access Personal Timesheet & Activity Logs"></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        Add Permission
                    </button>
                </form>
            </div>
        </div>

        <!-- 2. Right Column: Permissions List -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Search & Filters -->
            <div class="card bg-white border border-slate-200 rounded-xl shadow-sm p-4 flex items-center justify-between gap-4">
                <form action="{{ route('admin.permissions.index') }}" method="GET" class="flex-1 max-w-md">
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-3 text-slate-400">search</span>
                        <input type="text" name="search" placeholder="Search permissions..." value="{{ request('search') }}"
                            class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out" />
                    </div>
                </form>
                @if(request('search'))
                    <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-3 py-2 border border-slate-300 shadow-sm text-xs font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear Search
                    </a>
                @endif
            </div>

            <!-- List Card -->
            <div class="card bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <x-table :headers="['Permission Name', 'Feature / Usage', 'Group', 'Guard', 'Actions']" :striped="false">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100 group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                                        <span class="material-symbols-outlined text-sm">key</span>
                                    </div>
                                    <span class="font-bold text-slate-900 font-mono text-sm tracking-wide">{{ $permission->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm text-slate-600">{{ $permission->feature_description }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200 uppercase tracking-wider">
                                    {{ explode('-', $permission->name)[1] ?? 'general' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono bg-slate-50 text-slate-500 border border-slate-200 uppercase tracking-wider">
                                    {{ $permission->guard_name }}
                                </span>
                            </td>
                            <td class="text-right px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit Permission">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </a>
                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this permission? This could affect roles currently using it.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete Permission">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-4xl text-slate-300">key_off</span>
                                    <p class="font-medium text-slate-500 text-sm">No permissions found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </x-table>

                @if($permissions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                        {{ $permissions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
