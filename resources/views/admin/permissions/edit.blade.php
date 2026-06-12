<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Permission</h2>
                <p class="text-sm text-slate-500 mt-1">Modify permission key and its feature description mapping.</p>
            </div>
            <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <span class="material-symbols-outlined text-sm mr-2">arrow_back</span> Back to Permissions
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-900">Permission Details</h3>
                <p class="text-sm text-slate-500 mt-1">Update the system permission mapping details.</p>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 md:col-span-2">
                            <x-input-label for="name" :value="__('Permission Name')" class="text-slate-700 font-semibold" />
                            <x-text-input id="name" name="name" type="text" class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required :value="old('name', $permission->name)" placeholder="view-timesheet" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            <p class="text-xs text-slate-500 mt-1">Use lower case and hyphens for words (e.g., <code class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-mono text-[10px]">view-timesheet</code>).</p>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <x-input-label for="description" :value="__('Feature / Module Description')" class="text-slate-700 font-semibold" />
                            <textarea id="description" name="description" rows="4" 
                                class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm" 
                                placeholder="Describe what feature this permission governs... (e.g. Access Personal Timesheet & Activity Logs)">{{ old('description', $permission->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-200 flex justify-end gap-3">
                        <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            Update Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
