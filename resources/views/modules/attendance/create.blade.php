<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Manual Attendance</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Record a manual check-in entry.</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn btn-ghost btn-sm btn-outline">
                <span class="material-symbols-outlined text-base">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6 md:p-8">
                <form action="{{ route('attendance.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="form-control w-full">
                        <label for="employee_id" class="label">
                            <span class="label-text font-bold">Select Employee</span>
                        </label>
                        <select name="employee_id" id="employee_id" class="select select-bordered w-full">
                            <option value="">Choose an employee...</option>
                            @foreach(App\Modules\HR\Models\Employee::all() as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }} ({{ $employee->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control w-full">
                            <label for="date" class="label">
                                <span class="label-text font-bold">Date</span>
                            </label>
                            <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="input input-bordered w-full">
                        </div>

                        <div class="form-control w-full">
                            <label for="check_in" class="label">
                                <span class="label-text font-bold">Check In Time</span>
                            </label>
                            <input type="time" name="check_in" id="check_in" value="{{ date('H:i') }}" class="input input-bordered w-full">
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label for="remarks" class="label">
                            <span class="label-text font-bold">Remarks</span>
                        </label>
                        <textarea name="remarks" id="remarks" rows="3" placeholder="Reason for manual entry..." class="textarea textarea-bordered w-full"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('attendance.index') }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-primary">Record Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
