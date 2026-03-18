<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">New Leave Request</h2>
                <p class="text-xs font-medium mt-1 opacity-70">Submit your request for time away from the office.</p>
            </div>
            <a href="{{ route('leave.requests.index') }}" class="btn btn-ghost btn-sm btn-outline">
                <span class="material-symbols-outlined text-base">arrow_back</span> Back
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card bg-base-100 shadow-sm border border-base-200">
            <div class="card-body p-6 md:p-8">
                <form action="{{ route('leave.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-control w-full">
                            <label class="label" for="employee_id">
                                <span class="label-text font-bold">Employee</span>
                            </label>
                            <select id="employee_id" name="employee_id" class="select select-bordered w-full" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->full_name }} ({{ $employee->employee_id }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control w-full">
                            <label class="label" for="leave_type_id">
                                <span class="label-text font-bold">Leave Type</span>
                            </label>
                            <select id="leave_type_id" name="leave_type_id" class="select select-bordered w-full" required>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control w-full">
                            <label class="label" for="start_date">
                                <span class="label-text font-bold">Start Date</span>
                            </label>
                            <input id="start_date" name="start_date" type="date" class="input input-bordered w-full" required />
                        </div>

                        <div class="form-control w-full">
                            <label class="label" for="end_date">
                                <span class="label-text font-bold">End Date</span>
                            </label>
                            <input id="end_date" name="end_date" type="date" class="input input-bordered w-full" required />
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-base-200/50 p-4 rounded-xl border border-base-200">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="is_half_day" name="is_half_day" value="1" class="checkbox checkbox-primary checkbox-sm" onchange="toggleHalfDay(this)">
                            <label for="is_half_day" class="text-sm font-bold cursor-pointer">Half Day Request</label>
                        </div>
                        
                        <div id="half_day_options" class="hidden flex gap-2">
                            <select name="half_day_type" class="select select-bordered select-sm">
                                <option value="first_half">First Half</option>
                                <option value="second_half">Second Half</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label" for="reason">
                            <span class="label-text font-bold">Reason for Absence</span>
                        </label>
                        <textarea id="reason" name="reason" class="textarea textarea-bordered w-full" rows="3" required placeholder="Detail the reason..."></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary w-full">
                            Submit Request
                        </button>
                        <p class="text-center text-[10px] font-bold mt-4 uppercase tracking-widest opacity-60">Subject to administrative approval.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleHalfDay(checkbox) {
            const options = document.getElementById('half_day_options');
            if (checkbox.checked) {
                options.classList.remove('hidden');
            } else {
                options.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
