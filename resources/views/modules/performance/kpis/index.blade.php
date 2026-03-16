<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Key Performance Indicators</h1>
                    <p class="opacity-70 mt-1.5 font-medium">Define and track department-level metrics.</p>
                </div>
                <button onclick="kpi_modal.showModal()" class="btn btn-primary btn-sm flex items-center gap-2 px-5">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Define KPI
                </button>
            </div>

            <!-- KPIs Table -->
            <div class="card bg-base-100 border border-base-200 shadow-sm overflow-hidden min-h-[400px]">
                <div class="overflow-x-auto">
                    <table class="table table-md">
                        <thead class="bg-base-200/50">
                            <tr>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 opacity-70">KPI Name</th>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 opacity-70">Department</th>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 text-center opacity-70">Target</th>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 text-center opacity-70">Unit</th>
                                <th class="font-bold text-[11px] uppercase tracking-wider py-4 text-right opacity-70">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kpis as $kpi)
                                <tr class="hover:bg-base-200/50 transition-colors border-b border-base-200">
                                    <td class="py-4">
                                        <div class="font-semibold">{{ $kpi->name }}</div>
                                        <div class="text-xs mt-0.5 max-w-xs truncate opacity-70">{{ $kpi->description }}</div>
                                    </td>
                                    <td class="py-4">
                                        <span class="badge badge-sm font-bold badge-secondary">
                                            {{ $kpi->department->name ?? 'All Departments' }}
                                        </span>
                                    </td>
                                    <td class="text-center py-4 font-bold">{{ number_format($kpi->target_value, 2) }}</td>
                                    <td class="text-center py-4 font-medium capitalize opacity-70">{{ $kpi->unit }}</td>
                                    <td class="text-right py-4">
                                        <button class="btn btn-ghost btn-xs text-primary font-bold">Edit</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center italic font-medium opacity-50">No KPIs defined yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="p-4 border-t border-base-200 bg-base-200/30">
                    {{ $kpis->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <dialog id="kpi_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-100 border border-base-200 max-w-lg">
            <h3 class="font-bold text-lg mb-6">Define New KPI</h3>
            <form action="{{ route('performance.kpis.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">KPI Name</span></label>
                    <input type="text" name="name" required class="input input-bordered w-full" placeholder="e.g., Sales Target">
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered w-full" rows="3" placeholder="Describe the goal..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Department</span></label>
                        <select name="department_id" class="select select-bordered w-full">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Target Value</span></label>
                        <input type="number" name="target_value" required step="0.01" class="input input-bordered w-full" placeholder="0.00">
                    </div>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-xs uppercase tracking-widest">Unit</span></label>
                    <input type="text" name="unit" required class="input input-bordered w-full" placeholder="e.g., %, currency, count">
                </div>
                <div class="modal-action mt-8 pt-6 border-t border-base-200">
                    <button type="button" onclick="kpi_modal.close()" class="btn btn-ghost btn-sm font-bold uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-8 font-bold uppercase tracking-widest">Create KPI</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</x-app-layout>
